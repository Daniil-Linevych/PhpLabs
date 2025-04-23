<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Exhibition;
use App\Models\Visitor;
use App\Http\Requests\TicketRequest;
use App\Traits\Paginatable;

class TicketController extends Controller
{
    
    use Paginatable;

    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        $tickets_query = Ticket::query();
        $tickets = $this->paginateWithPerPage($tickets_query);
        return view('tickets.index', compact('tickets'));
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

        return view('tickets.create', compact('exhibitions', 'isUpdate'));
    }

    public function store(TicketRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $ticket = Ticket::create($request->validated());
        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $exhibitions = Exhibition::all();
        $isUpdate = true;

        return view('tickets.edit', compact('ticket', 'exhibitions', 'isUpdate'));
    }

    public function update(TicketRequest $request, Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $ticket->update($request->validated());
        return redirect()->route('tickets.index')->with('success', 'Ticket updated successfully.');
    }

    public function destroy(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user || !$user->hasRole('admin')){
            abort(403, 'Access denied!');
        }

        $ticket->delete();

        return redirect()->route('tickets.index')->with('success', 'Ticket deleted successfully!');
    }

    public function buy(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $id_user = auth()->id();

        if(!$user){
            abort(403, 'Access denied!');
        }
        $visitor = Visitor::where('user_id', $id_user)->first();
        $ticket->visitor()->associate($visitor);
        $ticket->save();
        
        return redirect()->route('tickets.index')->with('success', 'Ticket buyed successfully!');
    }

    public function sell(Ticket $ticket)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user){
            abort(403, 'Access denied!');
        }

        $ticket->visitor()->dissociate();
        $ticket->save();
        
        return redirect()->route('profile.index')->with('success', 'Ticket sold successfully!'); 
    }
}
