<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'user_book_id',
        'borrower_id',
        'borrow_date',
        'return_date',
    ];

    public function userBook()
    {
        return $this->belongsTo(UserBook::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }
}
