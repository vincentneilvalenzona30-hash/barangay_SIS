<?php

namespace App\Http\Controllers;

use App\Models\NewsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class NewsEventController extends Controller
{
    public function publicIndex()
    {
        $items = NewsEvent::where('is_published', true)
            ->latest()
            ->get();

        return Inertia::render('NewsEvents', [
            'newsItems' => $this->withImageUrls($items->where('type', 'News')->values()),
            'events' => $this->withImageUrls($items->where('type', 'Event')->values()),
        ]);
    }

    public function index()
    {
        return Inertia::render('Admin/NewsEvents/Index', [
            'items' => $this->withImageUrls(NewsEvent::latest()->get()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('news-events', 'public');
        }

        NewsEvent::create($validated);

        return redirect()->route('admin.news-events.index')->with('message', 'News or event added successfully.');
    }

    public function update(Request $request, NewsEvent $newsEvent)
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            if ($newsEvent->image_path) {
                Storage::disk('public')->delete($newsEvent->image_path);
            }

            $validated['image_path'] = $request->file('image')->store('news-events', 'public');
        }

        $newsEvent->update($validated);

        return redirect()->route('admin.news-events.index')->with('message', 'News or event updated successfully.');
    }

    public function destroy(NewsEvent $newsEvent)
    {
        if ($newsEvent->image_path) {
            Storage::disk('public')->delete($newsEvent->image_path);
        }

        $newsEvent->delete();

        return redirect()->route('admin.news-events.index')->with('message', 'News or event removed successfully.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'is_published' => $request->boolean('is_published'),
        ]);

        return $request->validate([
            'type' => 'required|in:News,Event',
            'title' => 'required|string|max:255',
            'tag' => 'nullable|string|max:60',
            'date_label' => 'nullable|string|max:120',
            'schedule' => 'nullable|string|max:160',
            'location' => 'nullable|string|max:160',
            'description' => 'required|string|max:3000',
            'image' => 'nullable|image|max:4096',
            'is_published' => 'boolean',
        ]);
    }

    private function withImageUrls($items)
    {
        return $items->map(fn (NewsEvent $item) => [
            ...$item->toArray(),
            'image_url' => $item->image_path ? '/storage/'.str_replace('\\', '/', $item->image_path) : null,
        ]);
    }
}
