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
        $visitors_query = Visitor::with('tickets');
        $visitors = $this->paginateWithPerPage($visitors_query);

        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        $isUpdate = false;
        return view('visitors.create', compact('isUpdate'));
    }

    public function store(VisitorRequest $request)
    {
        $visitor = Visitor::create($request->validated());
        return redirect()->route('visitors.index')->with('success', 'Visitor created successfully');
    }

    public function show(Visitor $visitor)
    {
        $visitor->load('tickets');
        return view('visitors.show', compact('visitor'));
    }

    public function edit(Visitor $visitor)
    {
        $isUpdate = true;
        return view('visitors.edit', compact('visitor','isUpdate'));
    }

    public function update(VisitorRequest $request, Visitor $visitor)
    {
        $visitor->update($request->validated());
        return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();
        return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully!');
    }
}
