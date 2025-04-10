<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Exhibition;
use App\Models\Visitor;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    
    public function index()
    {
        $tickets = Ticket::all();
        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('tickets.create', compact('exhibitions', 'isUpdate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'price' => 'required|numeric',
            'purchase_date' => 'required|date',
            'visitor_id' => 'nullable|exists:visitors,id',
            'exhibition_id' => 'required|exists:exhibitions,id',
        ]);

        $ticket = Ticket::create($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $exhibitions = Exhibition::all();
        $isUpdate = true;

        return view('tickets.edit', compact('ticket', 'exhibitions', 'isUpdate'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'price' => 'required|numeric',
            'purchase_date' => 'required|date',
            'exhibition_id' => 'required|exists:exhibitions,id',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    public function buy(Ticket $ticket)
    {
        $visitor = Visitor::find(1); 
        
        $ticket->visitor()->associate($visitor);
        $ticket->save();
        
        return redirect()->route('tickets.index')->with('success', 'Ticket buyed successfully!');
    }

    public function sell(Ticket $ticket)
    {
        $id = $ticket->getVisitorId();
        $ticket->visitor()->dissociate();
        $ticket->save();
        
        return redirect()->route('visitors.show', $id); 
    }
}
