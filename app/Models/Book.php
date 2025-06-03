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

    public function authors()
    {
        return $this->belongsToMany(Author::class, 'author_books', 'book_id', 'author_id')
                    ->withTimestamps();
    }

    public function shelves()
    {
        return $this->belongsToMany(Shelf::class, 'book_shelves', 'book_id', 'shelf_id')
                    ->withTimestamps();
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'book_genres', 'book_id', 'genre_id')
                    ->withTimestamps();
    } 

    public function userBooks()
    {
        return $this->hasMany(UserBook::class, 'book_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'book_id', 'id');
    }

    public function getCoverAttribute($value)
    {
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }

        return url('storage/' . $value);
    }
}
