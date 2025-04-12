<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Traits\Paginatable;

class StaffController extends Controller
{
    use Paginatable;
    
    public function index()
    {
        
        $staff_query = Staff::with('exhibitions');
        $staff = $this->paginateWithPerPage($staff_query);

        return view('staff.index', compact('staff'));
    }

    
    public function create()
    {
        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('staff.create', compact('exhibitions', 'isUpdate'));
    }


    public function store(StaffRequest $request)
    {
        $staff = Staff::create($request->validated());
        $staff->exhibitions()->attach($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }


    public function show(Staff $staff)
    {
        $staff->load('exhibitions');
        return view('staff.show', compact('staff'));
    }


    public function edit(Staff $staff)
    {
        $exhibitions = Exhibition::all();
        $staff->load('exhibitions');
        $isUpdate = true;
        return view('staff.edit', compact('staff', 'exhibitions', 'isUpdate'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        $staff->update($request->validated());
        $staff->exhibitions()->sync($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    public function destroy(Staff $staff)
    {
        $staff->exhibitions()->detach();
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully');
    }
}
