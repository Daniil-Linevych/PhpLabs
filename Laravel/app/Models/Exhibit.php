<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Exhibit extends Model
{
    /** @use HasFactory<\Database\Factories\ExhibitFactory> */
    use HasFactory;

    protected $table = 'exhibits';

    protected $fillable = [
        'name',
        'description',
        'creation_year',
        'author',
        'exhibition_id'
    ];

    protected $casts = [
        'creation_year' => 'integer',
    ];

    public function exhibition():BelongsTo{
        return $this->belongsTo(Exhibition::class);
    }
}
