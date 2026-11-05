<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category',
        'shelf_section',
        'cover_url',
        'rating',
        'price',
        'pages',
        'reading_progress',
        'synopsis',
        'published_year',
    ];
}
