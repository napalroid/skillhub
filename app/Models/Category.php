<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'icon'];

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    public function iconIsFile(): bool
    {
        return $this->icon && str_contains($this->icon, '/');
    }

    public function displayIcon(): string
    {
        if ($this->icon) {
            return $this->icon;
        }

        $name = Str::lower($this->name);

        return match (true) {
            Str::contains($name, ['desain', 'grafis', 'art']) => 'design',
            Str::contains($name, ['website', 'program', 'teknologi', 'coding']) => 'code',
            Str::contains($name, ['foto', 'video', 'film']) => 'camera',
            Str::contains($name, ['musik', 'audio']) => 'music',
            Str::contains($name, ['tulis', 'bahasa', 'terjemah']) => 'write',
            Str::contains($name, ['belajar', 'kursus', 'pendidikan']) => 'learn',
            Str::contains($name, ['bisnis', 'marketing', 'promosi']) => 'business',
            default => 'star',
        };
    }
}
