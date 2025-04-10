<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Staff;
use Illuminate\Http\Request;

class ExhibitionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exhibitions = Exhibition::all();
        return view('exhibitions.index', compact('exhibitions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $staffMembers = Staff::all();
        $isUpdate = false;
        return view('exhibitions.create', compact('staffMembers', 'isUpdate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'staff' => 'array',
            'staff.*' => 'exists:staff,id',
        ]);

        $exhibition = Exhibition::create($validated);
        $exhibition->staff()->attach($request->staff);

        return redirect()->route('exhibitions.index')->with('success', 'Exhibit created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Exhibition $exhibition)
    {
        $exhibition->load(['exhibits', 'tickets', 'staff']);
        return view('exhibitions.show', compact('exhibition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Exhibition $exhibition)
    {
        $staffMembers = Staff::all();
        $exhibition->load('staff');
        $isUpdate = true;
        return view('exhibitions.edit', compact('exhibition', 'staffMembers', 'isUpdate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exhibition $exhibition)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'staff' => 'required|array',
            'staff.*' => 'exists:staff,id'
        ]);

        $exhibition->update($validated);
        $exhibition->staff()->sync($request->staff);

        return redirect()->route('exhibitions.index')->with('success', 'Exhibition updated successfully.');
    }

    public function destroy(Exhibition $exhibition)
    {
        $exhibition->staff()->detach();
        $exhibition->delete();

        return redirect()->route('exhibitions.index')->with('success', 'Exhibition deleted successfully.');
    }
}
