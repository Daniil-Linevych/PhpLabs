<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use App\Models\Ticket;

class ProfileController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();

        if(!$user){
            abort(403, 'Access denied!');
        } elseif($user->hasRole('user')){
            $visitor = Visitor::where('user_id', $user->id)->first();
            $tickets = Ticket::where('visitor_id', $visitor->id)->get();

            return view('profile.index', ["user"=>$user, "tickets"=>$tickets]);
        } 
    
        return view('profile.index', ["user"=>$user]);
    }
}
