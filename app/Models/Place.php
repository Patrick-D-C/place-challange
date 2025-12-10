<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Place extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'state',
    ];

    public function setStateAttribute(string $value): void
    {
        $this->attributes['state'] = strtoupper($value);
    }

    protected static function booted(): void
    {
        static::creating(function (Place $place) {
            if (empty($place->slug)) {
                $place->slug = static::generateUniqueSlug($place->name);
            }
        });

        static::updating(function (Place $place) {
            if ($place->isDirty('name') && empty($place->slug)) {
                $place->slug = static::generateUniqueSlug($place->name, $place->id);
            }
        });
    }

    protected static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()
        ) {
            $slug = $baseSlug.'-'.$i++;
        }

        return $slug;
    }
}
