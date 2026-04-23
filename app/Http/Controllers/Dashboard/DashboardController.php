<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * Muestra la pantalla principal del dashboard.
     *
     * Si el usuario todavía no tiene empresa activa, mostramos el modal para crearla
     * y evitamos cargar métricas y gráficas del tenant.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $user->load('memberships:id,user_id,tenant_id,status');

        $mostrarModalEmpresa = $this->shouldShowCompanyModal($user);
        $periodo = $this->resolvePeriod($request);

        $metrics = [];
        $chartData = [];

        if (! $mostrarModalEmpresa) {
            $metrics = $this->dashboardService->getMetrics();
            $chartData = $this->dashboardService->getChartData($periodo);
        }

        $notes = $this->dashboardService->getRecentNotes(5);
        $reminders = $this->dashboardService->getActiveReminders(5);
        $events = $this->dashboardService->getUpcomingEvents(7);

        return view('dashboard.app.home', [
            'showModal' => $mostrarModalEmpresa,
            'period' => $periodo,
            'metrics' => $metrics,
            'chartData' => $chartData,
            'notes' => $notes,
            'reminders' => $reminders,
            'events' => $events,
        ]);
    }

    /**
     * Devuelve por AJAX las métricas y la gráfica para el período seleccionado.
     */
    public function chartData(Request $request): JsonResponse
    {
        $periodo = $this->resolvePeriod($request);

        return response()->json([
            'metrics' => $this->dashboardService->getMetrics(),
            'chartData' => $this->dashboardService->getChartData($periodo),
        ]);
    }

    /**
     * Decide si el usuario debe ver el modal para crear empresa.
     *
     * El modal se muestra cuando no tiene tenant actual y tampoco tiene
     * memberships activas asociadas.
     */
    private function shouldShowCompanyModal(User $user): bool
    {
        $tieneTenantActual = (bool) $user->current_tenant_id;
        $tieneMembershipActiva = $user->memberships->where('status', 'active')->isNotEmpty();

        return ! $tieneTenantActual && ! $tieneMembershipActiva;
    }

    /**
     * Lee el período desde la query string y aplica un valor por defecto seguro.
     */
    private function resolvePeriod(Request $request): string
    {
        $periodoSolicitado = $request->query('period');
        $periodosPermitidos = ['today', 'week', 'month'];

        if (in_array($periodoSolicitado, $periodosPermitidos, true)) {
            return $periodoSolicitado;
        }

        return 'week';
    }
}
