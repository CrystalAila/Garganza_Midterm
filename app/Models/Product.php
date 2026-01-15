<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'sku', 'price', 'stock_quantity', 'photo_path'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Accessor for public URL of stored photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            // Return a relative storage path so images load correctly regardless of APP_URL/port
            return '/storage/' . ltrim($this->photo_path, '/');
        }

        return null;
    }

    // Accessor for initials fallback
    public function getInitialsAttribute()
    {
        if (empty($this->name)) {
            return '';
        }

        $words = preg_split('/\s+/', trim($this->name));
        $initials = '';
        foreach ($words as $word) {
            if ($word === '') continue;
            $initials .= mb_strtoupper(mb_substr($word, 0, 1));
            if (mb_strlen($initials) >= 2) break;
        }

        return $initials;
    }
}