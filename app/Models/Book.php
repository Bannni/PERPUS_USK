<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'author',
        'publisher',
        'category',
        'shelf_section',
        'cover_image',
        'cover_url',
        'rating',
        'price',
        'pages',
        'reading_progress',
        'description',
        'synopsis',
        'published_year',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($book) {
            // Automatically ensure a stock record exists for this book (BNSP requirement)
            if (!$book->stock) {
                Stock::create([
                    'book_id' => $book->id,
                    'quantity' => 15, // default initial stock
                ]);
            }
        });
    }

    public function categoryRelation(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stock(): HasOne
    {
        return $this->hasOne(Stock::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function getCategoryAttribute($value)
    {
        return $this->categoryRelation?->name ?? $value ?? 'Umum';
    }

    public function getCoverUrlAttribute($value)
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://') || str_starts_with($this->cover_image, '/')) {
                return $this->cover_image;
            }
            return '/storage/' . $this->cover_image;
        }

        return $value ?? 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?auto=format&fit=crop&w=600&q=80';
    }

    public function getDescriptionAttribute($value)
    {
        return $value ?? $this->synopsis ?? 'Tidak ada deskripsi tersedia.';
    }

    public function getSynopsisAttribute($value)
    {
        return $value ?? $this->attributes['description'] ?? 'Tidak ada sinopsis tersedia.';
    }
}
