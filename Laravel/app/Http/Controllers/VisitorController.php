<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Ticket;
use Illuminate\Http\Request;
use App\Http\Requests\VisitorRequest;
use App\Traits\Paginatable;


class VisitorController extends Controller
{
    use Paginatable;
    
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $visitors_query = Visitor::with('tickets');
        $visitors = $this->paginateWithPerPage($visitors_query);

        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $isUpdate = false;
        return view('visitors.create', compact('isUpdate'));
    }

    public function store(VisitorRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $visitor = Visitor::create($request->validated());
        return redirect()->route('visitors.index')->with('success', 'Visitor created successfully');
    }

    public function show(Visitor $visitor)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $visitor->load('tickets');
        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $isUpdate = true;
        return view('visitors.edit', compact('visitor','isUpdate'));
    }

    public function update(VisitorRequest $request, Visitor $visitor)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $visitor->update($request->validated());
        return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully.');
    }

    public function destroy(Visitor $visitor)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully!');
    }
}
