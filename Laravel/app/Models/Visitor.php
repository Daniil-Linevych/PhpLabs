<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'user_id'
    ];
    protected $casts = [
        'registration_date' => 'date'
    ];

    public function tickets(): HasMany{
        return $this->hasMany(Ticket::class);
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'user_staff');
    }
}
