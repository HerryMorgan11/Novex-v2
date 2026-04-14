/**
 * Inicialización y configuración del módulo de Calendario.
 * Utiliza FullCalendar v6 y Vanilla JS nativo para la manipulación interactiva del DOM.
 */

import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

// Variables de estado global para la gestión de la lógica del modal
let currentAction = null; // Indica el contexto de la acción actual ('create' o 'edit')
let currentArg = null; // Almacena el payload temporal del evento o fecha seleccionada en FullCalendar

document.addEventListener('DOMContentLoaded', () => {
    // Extracción del contenedor raíz; aborta la ejecución si no existe la vista en el DOM actual
    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    // --- Referencias del DOM ---
    // Se almacenan en caché las referencias del HTML Dialog para optimizar el rendimiento y evitar búsquedas repetitivas en el árbol de nodos.
    const modal = document.getElementById('eventModal');
    const titleInput = document.getElementById('eventTitleInput');
    const titleLabel = document.getElementById('modalTitle');
    const btnCancel = document.getElementById('btnCancel');
    const btnSave = document.getElementById('btnSave');
    const btnDelete = document.getElementById('btnDelete');

    // --- Configuración de FullCalendar ---
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        locale: esLocale,
        height: '100%',
        initialView: 'dayGridMonth',

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay',
        },

        // Configuraciones de usabilidad de la UI
        navLinks: true,
        editable: true,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,

        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Agenda',
        },

        // Base de datos local (Mock data)
        // Se utilizan métodos del objeto Date nativo combinados con toISOString() para autogenerar
        // cadenas en formato estándar ISO-8601 (YYYY-MM-DD) interpretables unívocamente por FullCalendar.
        events: [
            {
                title: 'Revisión Contable',
                start: new Date().toISOString().split('T')[0] + 'T09:00:00',
                end: new Date().toISOString().split('T')[0] + 'T11:00:00',
            },
            {
                title: 'Reunión de Equipo',
                // Mutación dinámica de fecha: Suma 2 días al momento actual y trunca la cadena a la porción de fecha.
                start: new Date(new Date().setDate(new Date().getDate() + 2))
                    .toISOString()
                    .split('T')[0],
                allDay: true,
            },
        ],

        // --- Callbacks de Interacción de FullCalendar ---

        // Manejador para la creación de un nuevo evento al seleccionar una fecha o arrastrar en un rango.
        select: arg => {
            currentAction = 'create';
            currentArg = arg;

            titleLabel.innerText = 'Nuevo Evento';
            titleInput.value = '';
            btnDelete.style.display = 'none';

            // showModal() es una llamada a la API nativa de HTMLDialogElement que pausa interacciones de fondo y superpone un dialog sin ensuciar el DOM general.
            modal.showModal();
        },

        // Manejador preparatorio para la edición o eliminación al hacer clic sobre un evento preexistente.
        eventClick: arg => {
            currentAction = 'edit';
            currentArg = arg;

            titleLabel.innerText = 'Editar Evento';
            titleInput.value = arg.event.title;
            btnDelete.style.display = 'block';

            modal.showModal();
        },
    });

    // Construcción de la matriz y subnodos del calendario en el contenedor
    calendar.render();

    // --- Controladores del Modal HTML ---
    // Estos oyentes de eventos vinculan las interacciones reactivas del usuario con el motor de FullCalendar.

    btnCancel.onclick = () => {
        modal.close();
        calendar.unselect(); // Transacción abortada: se limpia proactivamente el bloque de selección temporal UI.
    };

    btnSave.onclick = () => {
        // La directiva .trim() sanitiza y purga de espacios residuales (invisible padding) la cadena de texto válida introducida por el usuario
        const title = titleInput.value.trim();
        if (!title) return; // Validación de integridad (Catcher robusto contra nulos)

        if (currentAction === 'create') {
            // Inyección de un nuevo objeto Event; 'allDay' mapea internamente su condición de franja horaria vs día completo.
            calendar.addEvent({
                title,
                start: currentArg.start,
                end: currentArg.end,
                allDay: currentArg.allDay,
            });
        } else {
            // Mutación "state-driven": Se actualiza el parámetro vía setProp().
            // Esta función subyacente dispara un re-renderizado automático, asilado a ese nodo exclusivamente, logrando un cambio visual instantáneo (Hot swap).
            currentArg.event.setProp('title', title);
        }
        modal.close();
    };

    btnDelete.onclick = () => {
        // Enmienda directa de destrucción a nivel del API de Eventos
        currentArg.event.remove();
        modal.close();
    };
});
