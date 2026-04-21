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
     * Muestra el dashboard principal.
     * Determina si mostrar el modal de creación de empresa en base al estado del tenant del usuario.
     *
     * Nota: la inicialización de tenancy la realiza el middleware `checkHasTenant`;
     * aquí no se debe llamar a Tenancy::initialize() para evitar doble inicialización.
     */
    public function index(Request $request): View
    {
        /** @var User $user */
        $user = $request->user();
        $user->load('memberships:id,user_id,tenant_id,status');

        // Mostrar modal si el usuario no tiene tenant ni membership activo
        $showModal = ! $user->current_tenant_id
            && ! $user->memberships->where('status', 'active')->count();

        $period = $this->resolvePeriod($request);

        $metrics = $showModal ? [] : $this->dashboardService->getMetrics();
        $chartData = $showModal ? [] : $this->dashboardService->getChartData($period);
        $notes = $this->dashboardService->getRecentNotes(5);
        $reminders = $this->dashboardService->getActiveReminders(5);
        $events = $this->dashboardService->getUpcomingEvents(7);

        return view('dashboard.app.home', [
            'showModal' => $showModal,
            'period' => $period,
            'metrics' => $metrics,
            'chartData' => $chartData,
            'notes' => $notes,
            'reminders' => $reminders,
            'events' => $events,
        ]);
    }

    /**
     * Endpoint AJAX para actualizar datos del dashboard según período.
     */
    public function chartData(Request $request): JsonResponse
    {
        return response()->json([
            'metrics' => $this->dashboardService->getMetrics(),
            'chartData' => $this->dashboardService->getChartData($this->resolvePeriod($request)),
        ]);
    }

    private function resolvePeriod(Request $request): string
    {
        return in_array($request->query('period'), ['today', 'week', 'month'], true)
            ? $request->query('period')
            : 'week';
    }
}
