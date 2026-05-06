<?php

namespace App\Services;

use App\Enums\Inventario\ExpedicionEstado;
use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\TransporteEstado;
use App\Models\Inventario\Expedicion;
use App\Models\Inventario\Lote;
use App\Models\Inventario\Movimiento;
use App\Models\Inventario\Producto;
use App\Models\Inventario\Stock;
use App\Models\Inventario\Transporte;
use App\Models\Note;
use App\Models\Reminder;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function __construct(private AuthFactory $auth) {}

    /**
     * KPIs principales para las cards de métricas.
     */
    public function getMetrics(): array
    {
        $notasTotal = 0;
        try {
            $notasTotal = Note::where('user_id', $this->authenticatedUserId())->count();
        } catch (\Throwable) {
            // Si la tabla no existe, devolver 0
        }

        return [
            'productos_activos' => Producto::where('estado_validacion', 'activo')->count(),
            'productos_borrador' => Producto::where('estado_validacion', 'borrador')->count(),
            'stock_total' => (float) Stock::sum(DB::raw('cantidad_actual - cantidad_reservada')),
            'transportes_pendientes' => Transporte::whereIn('estado', [
                TransporteEstado::Anunciado->value,
                TransporteEstado::PendienteUbicacion->value,
            ])->count(),
            'expediciones_activas' => Expedicion::whereIn('estado', [
                ExpedicionEstado::Preparada->value,
                ExpedicionEstado::Expedida->value,
                ExpedicionEstado::EnTransito->value,
            ])->count(),
            'lotes_almacenados' => Lote::where('estado', LoteEstado::Stored->value)->count(),
            'recordatorios_activos' => (function () {
                try {
                    return Reminder::where('user_id', $this->authenticatedUserId())
                        ->where('status', Reminder::STATUS_ACTIVE)
                        ->where('is_completed', false)
                        ->count();
                } catch (\Throwable) {
                    return 0;
                }
            })(),
            'notas_total' => $notasTotal,
        ];
    }

    /**
     * Datos de gráficas según período seleccionado.
     */
    public function getChartData(string $period = 'week'): array
    {
        $days = match ($period) {
            'today' => 1,
            'month' => 30,
            default => 7,
        };

        return [
            'movimientos' => $this->movimientosPorDia($days),
            'transportes_por_estado' => $this->transportesPorEstado(),
            'expediciones_por_estado' => $this->expedicionesPorEstado(),
            'stock_por_categoria' => $this->stockPorCategoria(),
        ];
    }

    private function movimientosPorDia(int $days): array
    {
        $from = now()->subDays($days - 1)->startOfDay();

        $rows = Movimiento::select(
            DB::raw('DATE(fecha) as dia'),
            DB::raw('COUNT(*) as total')
        )
            ->where('fecha', '>=', $from)
            ->groupBy('dia')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        $labels = [];
        $data = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('d/m');
            $data[] = (int) ($rows[$date]->total ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function transportesPorEstado(): array
    {
        $rows = Transporte::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $estado = $row->estado;
            if ($estado instanceof TransporteEstado) {
                $labels[] = $estado->label();
            } else {
                try {
                    $labels[] = TransporteEstado::from((string) $estado)->label();
                } catch (\ValueError) {
                    $labels[] = (string) $estado;
                }
            }
            $data[] = (int) $row->total;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function expedicionesPorEstado(): array
    {
        $rows = Expedicion::select('estado', DB::raw('COUNT(*) as total'))
            ->groupBy('estado')
            ->get();

        $labels = [];
        $data = [];

        foreach ($rows as $row) {
            $estado = $row->estado;
            if ($estado instanceof ExpedicionEstado) {
                $labels[] = $estado->label();
            } else {
                try {
                    $labels[] = ExpedicionEstado::from((string) $estado)->label();
                } catch (\ValueError) {
                    $labels[] = (string) $estado;
                }
            }
            $data[] = (int) $row->total;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    private function stockPorCategoria(): array
    {
        $rows = DB::table('stock')
            ->join('productos', 'stock.id_producto', '=', 'productos.id_producto')
            ->join('categorias_producto', 'productos.id_categoria', '=', 'categorias_producto.id_categoria')
            ->select(
                'categorias_producto.nombre as categoria',
                DB::raw('SUM(stock.cantidad_actual) as total')
            )
            ->groupBy('categorias_producto.nombre')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        return [
            'labels' => $rows->pluck('categoria')->toArray(),
            'data' => $rows->pluck('total')->map(fn ($v) => (float) $v)->toArray(),
        ];
    }

    /**
     * Últimas notas del usuario.
     */
    public function getRecentNotes(int $limit = 5): Collection
    {
        try {
            return Note::where('user_id', $this->authenticatedUserId())
                ->latest()
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            // Si la tabla no existe o hay error de conexión, devolver colección vacía
            return collect();
        }
    }

    /**
     * Recordatorios activos pendientes.
     */
    public function getActiveReminders(int $limit = 5): Collection
    {
        try {
            return Reminder::where('user_id', $this->authenticatedUserId())
                ->where('status', Reminder::STATUS_ACTIVE)
                ->where('is_completed', false)
                ->orderBy('due_at')
                ->limit($limit)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    /**
     * Próximos eventos (recordatorios con fecha) en los próximos N días.
     */
    public function getUpcomingEvents(int $days = 7): Collection
    {
        try {
            return Reminder::where('user_id', $this->authenticatedUserId())
                ->where('status', Reminder::STATUS_ACTIVE)
                ->where('is_completed', false)
                ->whereNotNull('due_at')
                ->where('due_at', '>=', now())
                ->where('due_at', '<=', now()->addDays($days))
                ->orderBy('due_at')
                ->limit(8)
                ->get();
        } catch (\Throwable) {
            return collect();
        }
    }

    private function authenticatedUserId(): int|string|null
    {
        return $this->auth->guard()->id();
    }
}
