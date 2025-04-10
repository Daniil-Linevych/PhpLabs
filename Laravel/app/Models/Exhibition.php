<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exhibition extends Model
{
    /** @use HasFactory<\Database\Factories\ExhibitionFactory> */
    use HasFactory;
    
    protected $table = 'exhibitions';

    protected $fillable = [
        'name',
        'start_date',
        'end_date'
    ];

    protected $casts = [
        'start_date'=>'date',
        'end_date'=>'date'
    ];

    public function exhibits(): HasMany{
        return $this->hasMany(Exhibit::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function staff(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'exhibition_staff')->withTimestamps();
    }

    public function getStaffMembersString(): string{
        if ($this->staff->isEmpty()){
            return 'Nobody assigned!';
        }
        $names = $this->staff->map(fn($member) => $member->getFullName())->toArray();
        return implode(', ', $names);
    }

    public function getName(): string {
        return $this->name;
    }
}
