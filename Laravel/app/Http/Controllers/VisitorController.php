<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VisitorController extends Controller
{
    
    public function index()
    {
        $visitors = Visitor::all();
        return view('visitors.index', compact('visitors'));
    }

    public function create()
    {
        $isUpdate = false;
        return view('visitors.create', compact('isUpdate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'registration_date' => 'required|date',
        ]);

        $visitor = Visitor::create($validated);
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

    public function update(Request $request, Visitor $visitor)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'registration_date' => 'required|date',
        ]);

        $visitor->update($validated);

        return redirect()->route('visitors.index')->with('success', 'Visitor updated successfully.');
    }

    public function destroy(Visitor $visitor)
    {
        $visitor->delete();

        return redirect()->route('visitors.index')->with('success', 'Visitor deleted successfully!');
    }
}
