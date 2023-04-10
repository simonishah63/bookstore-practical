<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Elasticquent\ElasticquentTrait;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    use ElasticquentTrait;

    protected $fillable = [
        'title',
        'author',
        'description',
        'genre',
        'isbn',
        'publisher',
        'published_at'
    ];

    protected $mappingProperties = [
        'title' => [
            'type' => 'keyword'
        ],
        'author' => [
            'type' => 'keyword'
        ],
        'genre' => [
            'type' => 'keyword'
        ],
        'isbn' => [
            'type' => 'keyword'
        ]
    ];
}
