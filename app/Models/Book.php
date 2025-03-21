<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'author_id',
        'title',
        'publisher',
        'published_date',
        'synopsis',
        'isbn',
        'pages',
        'cover',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class, 'book_shelves', 'book_id', 'shelf_id')
                    ->withTimestamps();
    }

    public function genres()
    {
        return $this->hasMany(Genre::class);
    }

}
