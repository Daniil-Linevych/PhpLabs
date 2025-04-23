<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use App\Models\Exhibition;
use Illuminate\Http\Request;
use App\Http\Requests\StaffRequest;
use App\Traits\Paginatable;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    use Paginatable;
    
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }
        
        $staff_query = Staff::with('exhibitions');
        $staff = $this->paginateWithPerPage($staff_query);

        return view('staff.index', compact('staff'));
    }

    
    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('staff.create', compact('exhibitions', 'isUpdate'));
    }


    public function store(StaffRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }
      
        do {
            $email = strtolower('worker' . uniqid() . '@gmail.com');
        } while (User::where('email', $email)->exists());
        
        $user = User::create([
            "name"=>$request->full_name,
            "email"=>$email,
            "password"=>Hash::make('123123'),
            "role_id"=>2
        ]);
        
        $staff = Staff::create([...$request->validated(), 'user_id' => $user->id]);

        $staff->exhibitions()->attach($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }


    public function show(Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $staff->load(['exhibitions', 'user']);
        return view('staff.show', compact('staff'));
    }


    public function edit(Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $exhibitions = Exhibition::all();
        $staff->load('exhibitions');
        $isUpdate = true;
        return view('staff.edit', compact('staff', 'exhibitions', 'isUpdate'));
    }

    public function update(StaffRequest $request, Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $staff->update($request->validated());
        $staff->exhibitions()->sync($request->exhibitions);

        return redirect()->route('staff.index')->with('success', 'Staff member created successfully');
    }

    public function destroy(Staff $staff)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $staff->exhibitions()->detach();
        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully');
    }
}
