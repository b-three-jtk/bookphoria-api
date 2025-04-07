<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use App\Models\UserBook;
use App\Models\UserFriend;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create test user
        $user = User::create([
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('password'),
            'fullname' => 'johndoe1',
            'gender' => 'Male'
        ]);

        // Create some books
        $books = Book::factory()->count(5)->create();

        // Add books to user's reading list
        foreach ($books as $book) {
            UserBook::create([
                'user_id' => $user->id,
                'book_id' => $book->id,
                'status' => 'reading',
                'page_count' => 0,
            ]);
        }

        // Create some friends
        $friends = User::factory()->count(3)->create();

        foreach ($friends as $friend) {
            UserFriend::create([
                'user_id' => $user->id,
                'friend_id' => $friend->id,
                'is_approved' => true,
            ]);
        }
    }
}
