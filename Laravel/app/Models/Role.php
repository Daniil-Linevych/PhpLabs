<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->permissions && in_array('*', $this->permissions)) {
            return true;
        }

        return $this->permissions && in_array($permission, $this->permissions);
    }

    public function givePermissionsTo(array $permissions): void
    {
        $this->update([
            'permissions' => array_unique(array_merge(
                $this->permissions ?? [],
                $permissions
            ))
        ]);
    }

    public static function findBySlug(string $slug): ?self
    {
        return self::where('slug', $slug)->first();
    }

}
