<?php

namespace App\Http\Controllers\Dashboard\Features\Notes;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreNoteRequest;
use App\Http\Requests\Notes\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * CRUD de notas personales del usuario autenticado.
 *
 * Todas las consultas están scopeadas por `user_id` para garantizar aislamiento
 * entre usuarios dentro del mismo tenant.
 */
class NoteController extends Controller
{
    /**
     * Muestra el listado de notas del usuario autenticado.
     */
    public function index(): View
    {
        $usuarioId = request()->user()?->getAuthIdentifier();

        $notes = Note::query()
            ->where('user_id', $usuarioId)
            ->select('id', 'user_id', 'title', 'content', 'created_at', 'updated_at')
            ->latest()
            ->get();

        return view('dashboard.features.notes.index', compact('notes'));
    }

    /**
     * Muestra el formulario de creación de nota.
     */
    public function create(): View
    {
        return view('dashboard.features.notes.create');
    }

    /**
     * Almacena una nueva nota en la base de datos del tenant.
     */
    public function store(StoreNoteRequest $request): RedirectResponse
    {
        $usuarioId = $request->user()?->getAuthIdentifier();
        $datosValidados = $request->validated();

        $datosValidados['user_id'] = $usuarioId;

        Note::create($datosValidados);

        return $this->backToIndex('Nota creada correctamente.');
    }

    /**
     * Muestra el formulario de edición de una nota.
     */
    public function edit(int $id): View
    {
        return view('dashboard.features.notes.edit', ['note' => $this->findOwned($id)]);
    }

    /**
     * Actualiza una nota existente del usuario.
     */
    public function update(UpdateNoteRequest $request, int $id): RedirectResponse
    {
        $this->findOwned($id)->update($request->validated());

        return $this->backToIndex('Nota actualizada.');
    }

    /**
     * Elimina una nota del usuario.
     */
    public function destroy(int $id): RedirectResponse
    {
        $this->findOwned($id)->delete();

        return $this->backToIndex('Nota eliminada.');
    }

    /**
     * Recupera una nota garantizando que pertenece al usuario autenticado.
     */
    private function findOwned(int $id): Note
    {
        $usuarioId = request()->user()?->getAuthIdentifier();

        return Note::where('user_id', $usuarioId)->findOrFail($id);
    }

    /**
     * Redirige al listado de notas con un mensaje flash.
     */
    private function backToIndex(string $message): RedirectResponse
    {
        return redirect()->route('dashboard.features.notes.index')->with('success', $message);
    }
}
