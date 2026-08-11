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
            Str::contains($name, ['desain', 'grafis', 'art']) => '🎨',
            Str::contains($name, ['website', 'program', 'teknologi', 'coding']) => '💻',
            Str::contains($name, ['foto', 'video', 'film']) => '📷',
            Str::contains($name, ['musik', 'audio']) => '🎵',
            Str::contains($name, ['tulis', 'bahasa', 'terjemah']) => '✍️',
            Str::contains($name, ['belajar', 'kursus', 'pendidikan']) => '📚',
            Str::contains($name, ['bisnis', 'marketing', 'promosi']) => '📈',
            default => '✨',
        };
    }
}
