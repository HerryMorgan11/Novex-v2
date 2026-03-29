<?php

namespace App\Http\Controllers\Dashboard\Features;

use App\Http\Controllers\Controller;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index()
    {
        return view('dashboard.features.notes.index');
    }

    public function create(Request $request)
    {
        $note = Note::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dashboard.features.notes.index');
    }

    public function edit(Request $request)
    {
        $note = Note::find($request->id);
        $note->title = $request->title;
        $note->content = $request->content;
        $note->save();

        return redirect()->route('dashboard.features.notes.index');
    }

    public function delete(Request $request)
    {
        $note = Note::find($request->id);
        $note->delete();

        return redirect()->route('dashboard.features.notes.index');
    }
}
