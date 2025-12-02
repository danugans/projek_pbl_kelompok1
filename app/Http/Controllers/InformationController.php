<?php

namespace App\Http\Controllers;

use App\Models\Information;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $informations = Information::when($search, function ($query, $search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('content', 'like', "%$search%");
        })->latest()->paginate(5);

        return view('event.index', compact('informations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255|min:2',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('information', 'public');
        }

        Information::create($validated);

        return redirect()->route('submit-event.index')->with('success', 'Event berhasil ditambahkan.');
    }

    public function show($id)
    {
        $information = Information::findOrFail($id);
        return view('event.show', compact('information'));
    }

    public function update(Request $request, $id)
    {
        $information = Information::findOrFail($id);

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ]);

        if ($request->hasFile('image')) {
            if ($information->image) {
                Storage::disk('public')->delete($information->image);
            }
            $validated['image'] = $request->file('image')->store('information', 'public');
        }

        $information->update($validated);

        return redirect()->route('submit-event.index')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $information = Information::findOrFail($id);

        if ($information->image) {
            Storage::disk('public')->delete($information->image);
        }

        $information->delete();

        return redirect()->route('submit-event.index')->with('success', 'Event berhasil dihapus.');
    }
}
