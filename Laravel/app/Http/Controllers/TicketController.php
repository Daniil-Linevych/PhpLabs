<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Exhibition;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\Http\Requests\TicketRequest;
use App\Traits\Paginatable;

class TicketController extends Controller
{
    
    use Paginatable;

    public function index()
    {
        $tickets_query = Ticket::query();
        $tickets = $this->paginateWithPerPage($tickets_query);

        return view('tickets.index', compact('tickets'));
    }

    public function create()
    {
        $exhibitions = Exhibition::all();
        $isUpdate = false;

        return view('tickets.create', compact('exhibitions', 'isUpdate'));
    }

    public function store(TicketRequest $request)
    {
        $ticket = Ticket::create($request->validated());
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

    public function update(TicketRequest $request, Ticket $ticket)
    {
        $ticket->update($request->validated());
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
