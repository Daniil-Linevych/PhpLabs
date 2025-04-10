<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Staff extends Model
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory;

    protected $fillable = [
        'full_name',
        'position',
        'phone',
        'salary',
        'hire_date'
    ];

    protected $casts = [
        'salary' => 'float',
        'hire_date' => 'date',
    ];

    public function exhibitions(): BelongsToMany
    {
        return $this->belongsToMany(Exhibition::class, 'exhibition_staff')->withTimestamps();
    }

    public function getFullName(): string
    {
        return $this->full_name;
    }

    public function getExhibitionsString(): string{
        if ($this->exhibitions->isEmpty()){
            return 'No one assigned!';
        }
        $names = $this->exhibitions->map(fn($exhibition) => $exhibition->getName())->toArray();
        return implode(', ', $names);
    }

    
}
