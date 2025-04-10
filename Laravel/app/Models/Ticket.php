<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    /** @use HasFactory<\Database\Factories\TicketFactory> */
    use HasFactory;

    protected $table = 'tickets';

    protected $fillable = [
        'price',
        'purchase_date',
        'visitor_id',
        'exhibition_id'
    ];

    protected $casts = [
        'price' => 'float',
        'purchase_date' => 'datetime',
    ];

    public function visitor(): BelongsTo{
        return $this->belongsTo(Visitor::class);
    }

    public function exhibition(): BelongsTo{
        return $this->belongsTo(Exhibition::class);
    }

    public function getVisitorId():int{
        return $this->visitor_id;
    }
}
