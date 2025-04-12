<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Traits\Paginatable;

class ExhibitionController extends Controller
{
    use Paginatable;

    public function index()
    {
        $exhibitions_query = Exhibition::with('staff');
        $exhibitions = $this->paginateWithPerPage($exhibitions_query);

        return view('exhibitions.index', compact('exhibitions'));
    }

    public function create()
    {
        $staffMembers = Staff::all();
        $isUpdate = false;
        return view('exhibitions.create', compact('staffMembers', 'isUpdate'));
    }

    public function store(ExhibitionRequest $request)
    {
        $exhibition = Exhibition::create($request->validated());
        $exhibition->staff()->attach($request->staff);

        return redirect()->route('exhibitions.index')->with('success', 'Exhibit created successfully');
    }

    public function show(Exhibition $exhibition)
    {
        $exhibition->load(['exhibits', 'tickets', 'staff']);
        return view('exhibitions.show', compact('exhibition'));
    }

    public function edit(Exhibition $exhibition)
    {
        $staffMembers = Staff::all();
        $exhibition->load('staff');
        $isUpdate = true;
        return view('exhibitions.edit', compact('exhibition', 'staffMembers', 'isUpdate'));
    }

    public function update(ExhibitionRequest $request, Exhibition $exhibition)
    {
        $exhibition->update($request->validated());
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
