<?php

namespace App\Http\Controllers;

use App\Models\Exhibit;
use App\Models\Exhibition;
use App\Http\Requests\ExhibitRequest;
use App\Traits\Paginatable;
use Illuminate\Http\Request;

class ExhibitController extends Controller
{
    use Paginatable;
    
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        //$exhibits_query = Exhibit::with('exhibition');
        $exhibits_query = Exhibit::with('exhibition')
        ->when($request->filled('name'), function ($q) use ($request) {
            $q->where('name', 'like', '%' . $request->input('name') . '%');
        })
        ->when($request->filled('author'), function ($q) use ($request) {
            $q->where('author', 'like', '%' . $request->input('author') . '%');
        })
        ->when($request->filled('creation_year'), function ($q) use ($request) {
            $this->applyCreationYearFilter($q, 'creation_year', $request->input('creation_year'));
        })
        ->when($request->filled('exhibition'), function ($q) use ($request) {
            $this->applyExhibitionFilter($q, $request->input('exhibition'));
        });

        $exhibits = $this->paginateWithPerPage($exhibits_query);

        return view('exhibits.index', compact('exhibits'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('exhibits.create', compact('exhibitions', 'isUpdate'));
    }

    public function store(ExhibitRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $exhibit = Exhibit::create($request->validated());
        return redirect()->route('exhibits.index')->with('success', 'Exhibit created successfully');
    }

    public function show(Exhibit $exhibit)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        return view('exhibits.show', compact('exhibit'));
    }

    public function edit(Exhibit $exhibit)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $exhibitions = Exhibition::all();
        $isUpdate = true;

        return view('exhibits.edit', compact('exhibit', 'exhibitions', 'isUpdate'));
    }

    public function update(ExhibitRequest $request, Exhibit $exhibit)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $exhibit->update($request->validated());
        return redirect()->route('exhibits.index')->with('success', 'Exhibit updated successfully.');
    }

    public function destroy(Exhibit $exhibit)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || $user->hasRole('user')){
            abort(403, 'Access denied!');
        }

        $exhibit->delete();

        return redirect()->route('exhibits.index')->with('success', 'Exhibit deleted successfully!');
    }

    private function applyCreationYearFilter($query, string $field, string $value): void
{
    try {
        $operator = '=';
        $yearValue = $value;
        
        if (preg_match('/^(>|<|>=|<=|=)?\s*(\d+)$/', $value, $matches)) {
            $operator = $matches[1] ?: '=';
            $yearValue = (int)$matches[2];
        }
    
        $query->whereRaw("{$field}::integer {$operator} ?", [$yearValue]);
        
    } catch (\Exception $e) {
        return;
    }
}

    private function applyExhibitionFilter($query, $value): void
    {
        $query->whereHas('exhibition', function($q) use ($value) {
            $q->where('name', 'like', '%'.$value.'%');
        });
    }
}
