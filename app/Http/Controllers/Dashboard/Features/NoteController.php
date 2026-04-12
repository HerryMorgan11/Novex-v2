<?php

namespace App\Http\Controllers\Dashboard\Features;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', auth()->id())->latest()->get();

        return view('dashboard.features.notes.index', compact('notes'));
    }

    public function create()
    {
        return view('dashboard.features.notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard.features.notes.index')->with('success', 'Nota creada correctamente.');
    }

    public function edit($id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        return view('dashboard.features.notes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return redirect()->route('dashboard.features.notes.index')->with('success', 'Nota actualizada.');
    }

    public function destroy($id)
    {
        $note = Note::findOrFail($id);

        if ($note->user_id !== auth()->id()) {
            abort(403);
        }

        $note->delete();

        return redirect()->route('dashboard.features.notes.index')->with('success', 'Nota eliminada.');
    }
}
