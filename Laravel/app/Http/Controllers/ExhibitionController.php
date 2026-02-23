<?php

namespace App\Http\Controllers;

use App\Models\Exhibition;
use App\Models\Staff;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\ExhibitionRequest;
use App\Traits\Paginatable;

class ExhibitionController extends Controller
{
    use Paginatable;

    public function index(Request $request)
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
            //$exhibitions_query = Exhibition::with('staff');
            $exhibitions_query = Exhibition::with('staff')
                ->when($request->filled('name'), function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->input('name') . '%');
                })
                ->when($request->filled('start_date'), function ($q) use ($request) {
                    $this->applyDateFilter($q, 'start_date', $request->input('start_date'));
                })
                ->when($request->filled('end_date'), function ($q) use ($request) {
                    $this->applyDateFilter($q, 'end_date', $request->input('end_date'));
                })
                ->when($request->filled('staff'), function ($q) use ($request) {
                    $this->applyStaffFilter($q, $request->input('staff'));
                });
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

    private function applyDateFilter($query, string $field, $value)
{
    try {
        if (str_contains($value, '..')) {
            [$start, $end] = explode('..', $value, 2);
            $query->whereBetween($field, [
                Carbon::parse(trim($start))->startOfDay(),
                Carbon::parse(trim($end))->endOfDay()
            ]);
            return;
        }

        if (preg_match('/^(>|<|>=|<=)\s*(.*)/', $value, $matches)) {
            $operator = $matches[1];
            $date = \Carbon\Carbon::parse(trim($matches[2]));
            $query->where($field, $operator, $date);
            return;
        }

        $date = \Carbon\Carbon::parse($value);
        $query->whereBetween($field, [
            $date->startOfDay(),
            $date->endOfDay()
        ]);

    } catch (\Exception $e) {
        return;
    }
}

private function applyStaffFilter($query, $value)
{
    $query->whereHas('staff', function ($q) use ($value) {
        
            $q->where(function ($q) use ($value) {
                $q->where('full_name', 'like', '%' . $value . '%');
            });
    });
}
}
