<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Searchable;

class Book extends Model
{
    use HasFactory;
    
    use SoftDeletes;

    use Searchable;

    protected $fillable = [
        'title',
        'author',
        'description',
        'genre',
        'isbn',
        'publisher',
        'published_at',
        'image'
    ];
}
