<?php

namespace Database\Seeders;

use App\Models\Reminder;
use App\Models\ReminderList;
use App\Models\Subtask;
use App\Models\Tag;
use Illuminate\Database\Seeder;

/**
 * Seeder de prueba para el módulo de recordatorios.
 * Usar únicamente en tenant databases (tenancy debe estar inicializada).
 *
 * Uso:
 *   php artisan tenants:run db:seed --option="class=ReminderSeeder"
 */
class ReminderSeeder extends Seeder
{
    public function run(): void
    {
        $userId = \Illuminate\Support\Facades\Auth::id()
            ?? \App\Models\User::first()?->id;

        if (! $userId) {
            $this->command->warn('No hay usuario disponible para el seeder.');

            return;
        }

        // Crear listas
        $listPersonal = ReminderList::create([
            'user_id' => $userId,
            'name' => 'Personal',
            'color' => '#007aff',
            'icon' => 'home',
            'is_default' => true,
            'position' => 0,
        ]);

        $listTrabajo = ReminderList::create([
            'user_id' => $userId,
            'name' => 'Trabajo',
            'color' => '#ff9500',
            'icon' => 'briefcase',
            'position' => 1,
        ]);

        $listCompras = ReminderList::create([
            'user_id' => $userId,
            'name' => 'Compras',
            'color' => '#34c759',
            'icon' => 'cart',
            'position' => 2,
        ]);

        // Crear etiquetas
        $tagUrgente = Tag::create(['user_id' => $userId, 'name' => 'Urgente', 'color' => '#ff3b30']);
        $tagImportante = Tag::create(['user_id' => $userId, 'name' => 'Importante', 'color' => '#ff9500']);
        $tagSalud = Tag::create(['user_id' => $userId, 'name' => 'Salud', 'color' => '#34c759']);

        // Crear recordatorios
        $r1 = Reminder::create([
            'user_id' => $userId,
            'reminder_list_id' => $listPersonal->id,
            'title' => 'Llamar al médico',
            'notes' => 'Pedir cita para revisión anual',
            'priority' => Reminder::PRIORITY_HIGH,
            'due_at' => now()->addDays(3),
            'position' => 0,
        ]);
        $r1->tags()->attach([$tagUrgente->id, $tagSalud->id]);

        $r2 = Reminder::create([
            'user_id' => $userId,
            'reminder_list_id' => $listTrabajo->id,
            'title' => 'Preparar presentación del proyecto',
            'notes' => 'Slides + demo funcional para el cliente',
            'priority' => Reminder::PRIORITY_HIGH,
            'due_at' => now()->addDays(5),
            'position' => 0,
        ]);
        $r2->tags()->attach([$tagUrgente->id, $tagImportante->id]);

        // Subtareas para la presentación
        Subtask::create(['reminder_id' => $r2->id, 'title' => 'Crear slides de introducción', 'position' => 0]);
        Subtask::create(['reminder_id' => $r2->id, 'title' => 'Preparar demo en vivo', 'position' => 1]);
        Subtask::create(['reminder_id' => $r2->id, 'title' => 'Revisar datos del cliente', 'position' => 2, 'is_completed' => true, 'completed_at' => now()]);

        $r3 = Reminder::create([
            'user_id' => $userId,
            'reminder_list_id' => $listCompras->id,
            'title' => 'Compras del supermercado',
            'priority' => Reminder::PRIORITY_LOW,
            'all_day' => true,
            'due_at' => now()->addDays(1),
            'position' => 0,
        ]);

        Subtask::create(['reminder_id' => $r3->id, 'title' => 'Leche', 'position' => 0]);
        Subtask::create(['reminder_id' => $r3->id, 'title' => 'Pan', 'position' => 1]);
        Subtask::create(['reminder_id' => $r3->id, 'title' => 'Frutas y verduras', 'position' => 2]);

        // Recordatorio vencido (para probar filtro overdue)
        Reminder::create([
            'user_id' => $userId,
            'reminder_list_id' => $listPersonal->id,
            'title' => 'Pagar el recibo del agua',
            'priority' => Reminder::PRIORITY_MEDIUM,
            'due_at' => now()->subDays(2),
            'position' => 1,
        ]);

        // Recordatorio completado
        $r5 = Reminder::create([
            'user_id' => $userId,
            'reminder_list_id' => $listPersonal->id,
            'title' => 'Renovar el carné de biblioteca',
            'is_completed' => true,
            'completed_at' => now()->subDay(),
            'position' => 2,
        ]);

        $this->command->info('✅ ReminderSeeder completado con éxito.');
        $this->command->line('   - 3 listas creadas');
        $this->command->line('   - 3 etiquetas creadas');
        $this->command->line('   - 5 recordatorios creados (1 vencido, 1 completado)');
        $this->command->line('   - 5 subtareas creadas');
    }
}
