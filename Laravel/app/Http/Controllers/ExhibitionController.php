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
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        if (auth()->check() && $user->hasRole('worker')) {
            $staff = Staff::where('user_id', auth()->id())->first();
            
            if ($staff) {
                $exhibitions_query = Exhibition::whereHas('staff', function ($q) use ($staff) {
                    $q->where('staff.id', $staff->id);
                })->with('staff');
            } else {
                $exhibitions_query = Exhibition::where('id', 0); 
            }
        } else {
            $exhibitions_query = Exhibition::with('staff');
        }
        

        $exhibitions = $this->paginateWithPerPage($exhibitions_query);

        return view('exhibitions.index', compact('exhibitions'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $staffMembers = Staff::all();
        $isUpdate = false;
        return view('exhibitions.create', compact('staffMembers', 'isUpdate'));
    }

    public function store(ExhibitionRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }


        $exhibition = Exhibition::create($request->validated());
        $exhibition->staff()->attach($request->staff);

        return redirect()->route('exhibitions.index')->with('success', 'Exhibit created successfully');
    }

    public function show(Exhibition $exhibition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        if($user->hasRole('worker')){
            $worker = Staff::where('user_id', auth()->id())->first();
            if (!$exhibition->staff->contains($worker)){
                abort(403, 'Access denied!');
            }
        }

        $exhibition->load(['exhibits', 'tickets', 'staff']);
        return view('exhibitions.show', compact('exhibition'));
    }

    public function edit(Exhibition $exhibition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        if($user->hasRole('worker')){
            $worker = Staff::where('user_id', auth()->id())->first();
            if (!$exhibition->staff->contains($worker)){
                abort(403, 'Access denied!');
            }
        }

        $staffMembers = Staff::all();
        $exhibition->load('staff');
        $isUpdate = true;
        return view('exhibitions.edit', compact('exhibition', 'staffMembers', 'isUpdate'));
    }

    public function update(ExhibitionRequest $request, Exhibition $exhibition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        if($user->hasRole('worker')){
            $worker = Staff::where('user_id', auth()->id())->first();
            if (!$exhibition->staff->contains($worker)){
                abort(403, 'Access denied!');
            }
        }

        $exhibition->update($request->validated());
        $exhibition->staff()->sync($request->staff);

        return redirect()->route('exhibitions.index')->with('success', 'Exhibition updated successfully.');
    }

    public function destroy(Exhibition $exhibition)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        if($user->hasRole('worker')){
            $worker = Staff::where('user_id', auth()->id())->first();
            if (!$exhibition->staff->contains($worker)){
                abort(403, 'Access denied!');
            }
        }

        $exhibition->staff()->detach();
        $exhibition->delete();

        return redirect()->route('exhibitions.index')->with('success', 'Exhibition deleted successfully.');
    }
}
