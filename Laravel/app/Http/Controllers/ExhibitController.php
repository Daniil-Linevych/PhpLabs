<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Models\Exhibition;
use Illuminate\Http\Request;

class ExhibitController extends Controller
{
    
    public function index()
    {
        $exhibits = Exhibit::all();
        return view('exhibits.index', compact('exhibits'));
    }

    public function create()
    {
        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('exhibits.create', compact('exhibitions', 'isUpdate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'required|string|max:255',
            'exhibition_id' => 'required|exists:exhibitions,id',
            'creation_year' => 'required|integer|max:2025',
        ]);

        $exhibit = Exhibit::create($validated);

        return redirect()->route('exhibits.index')->with('success', 'Exhibit created successfully');
    }

    public function show(Exhibit $exhibit)
    {
        return view('exhibits.show', compact('exhibit'));
    }

    public function edit(Exhibit $exhibit)
    {
        $exhibitions = Exhibition::all();
        $isUpdate = true;

        return view('exhibits.edit', compact('exhibit', 'exhibitions', 'isUpdate'));
    }

    public function update(Request $request, Exhibit $exhibit)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author' => 'required|string|max:255',
            'exhibition_id' => 'required|exists:exhibitions,id',
            'creation_year' => 'required|integer|max:2025',
        ]);

        $exhibit->update($validated);

        return redirect()->route('exhibits.index')->with('success', 'Exhibit updated successfully.');
    }

    public function destroy(Exhibit $exhibit)
    {
        $exhibit->delete();

        return redirect()->route('exhibits.index')->with('success', 'Exhibit deleted successfully!');
    }
}
