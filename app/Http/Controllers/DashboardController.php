<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Stancl\Tenancy\Facades\Tenancy;

class DashboardController extends Controller
{
    public function __construct(protected DashboardService $dashboardService) {}

    /**
     * Muestra el dashboard principal.
     * Determina si mostrar el modal de creación de empresa en base al estado del tenant del usuario.
     */
    public function index(Request $request): View
    {
        $user = auth()->user()->load('memberships:id,user_id,tenant_id,status');

        // Mostrar modal si el usuario no tiene tenant ni membership activo
        $showModal = ! $user->current_tenant_id
            && ! $user->memberships->where('status', 'active')->count();

        $period = in_array($request->query('period'), ['today', 'week', 'month'])
            ? $request->query('period')
            : 'week';

        // Si el usuario tiene tenant, inicializamos tenancy para poder consultar inventario
        if ($user->current_tenant_id) {
            Tenancy::initialize($user->current_tenant_id);
        }

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
        $period = in_array($request->query('period'), ['today', 'week', 'month'])
            ? $request->query('period')
            : 'week';

        $user = auth()->user();
        if ($user->current_tenant_id) {
            Tenancy::initialize($user->current_tenant_id);
        }

        return response()->json([
            'metrics' => $this->dashboardService->getMetrics(),
            'chartData' => $this->dashboardService->getChartData($period),
        ]);
    }
}
