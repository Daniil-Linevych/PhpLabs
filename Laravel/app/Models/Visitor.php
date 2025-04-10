<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    /** @use HasFactory<\Database\Factories\VisitorFactory> */
    use HasFactory;

    protected $table = 'visitors';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'registration_date',
    ];
    protected $casts = [
        'registration_date' => 'date'
    ];

    public function tickets(): HasMany{
        return $this->hasMany(Ticket::class);
    }
}
