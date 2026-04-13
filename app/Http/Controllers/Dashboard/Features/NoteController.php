<?php

namespace App\Http\Controllers\Dashboard\Features;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    /**
     * Lista las notas del usuario autenticado.
     */
    public function index(): View
    {
        $notes = Note::where('user_id', auth()->id())
            ->select('id', 'user_id', 'title', 'content', 'created_at', 'updated_at')
            ->latest()
            ->get();

        return view('dashboard.features.notes.index', compact('notes'));
    }

    /**
     * Formulario de creación de nota.
     */
    public function create(): View
    {
        return view('dashboard.features.notes.create');
    }

    /**
     * Guarda una nueva nota.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        Note::create([...$data, 'user_id' => auth()->id()]);

        return redirect()->route('dashboard.features.notes.index')
            ->with('success', 'Nota creada correctamente.');
    }

    /**
     * Formulario de edición de nota.
     */
    public function edit(int $id): View
    {
        // Busca la nota garantizando que pertenece al usuario autenticado
        $note = Note::where('user_id', auth()->id())->findOrFail($id);

        return view('dashboard.features.notes.edit', compact('note'));
    }

    /**
     * Actualiza una nota existente.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $note = Note::where('user_id', auth()->id())->findOrFail($id);

        $note->update($request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]));

        return redirect()->route('dashboard.features.notes.index')
            ->with('success', 'Nota actualizada.');
    }

    /**
     * Elimina una nota.
     */
    public function destroy(int $id): RedirectResponse
    {
        Note::where('user_id', auth()->id())->findOrFail($id)->delete();

        return redirect()->route('dashboard.features.notes.index')
            ->with('success', 'Nota eliminada.');
    }
}
