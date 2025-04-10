<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Exhibition;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = Staff::all();
        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('staff.create', compact('exhibitions', 'isUpdate'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'exhibitions' => 'array',
            'exhibitions.*' => 'exists:exhibitions,id',
        ]);

        $staff = Staff::create($validated);
        $staff->exhibitions()->attach($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Staff $staff)
    {
        $staff->load('exhibitions');
        return view('staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        $exhibitions = Exhibition::all();
        $staff->load('exhibitions');
        $isUpdate = true;
        return view('staff.edit', compact('staff', 'exhibitions', 'isUpdate'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'exhibitions' => 'array',
            'exhibitions.*' => 'exists:exhibitions,id',
        ]);

        $staff->update($validated);
        $staff->exhibitions()->sync($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->exhibitions()->detach();
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully');
    }
}
