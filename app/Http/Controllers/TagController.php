<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::forUser(auth()->user())
            ->ordered()
            ->withCount('reminders')
            ->get();

        return view('dashboard.features.reminders.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('dashboard.features.reminders.tags.create');
    }

    public function store(StoreTagRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();

        Tag::create($data);

        return redirect()->route('reminders.tags.index')
            ->with('success', 'Etiqueta creada correctamente.');
    }

    public function show(Tag $tag): View
    {
        $this->authorize('view', $tag);

        $reminders = $tag->reminders()
            ->with(['list', 'tags', 'subtasks'])
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->orderBy('position')
            ->paginate(20);

        return view('dashboard.features.reminders.tags.show', compact('tag', 'reminders'));
    }

    public function edit(Tag $tag): View
    {
        $this->authorize('update', $tag);

        return view('dashboard.features.reminders.tags.edit', compact('tag'));
    }

    public function update(UpdateTagRequest $request, Tag $tag): RedirectResponse
    {
        $this->authorize('update', $tag);

        $tag->update($request->validated());

        return redirect()->route('reminders.tags.index')
            ->with('success', 'Etiqueta actualizada correctamente.');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $this->authorize('delete', $tag);

        $tag->reminders()->detach(); // Limpiar pivot antes de eliminar
        $tag->delete();

        return redirect()->route('reminders.tags.index')
            ->with('success', 'Etiqueta eliminada.');
    }
}
