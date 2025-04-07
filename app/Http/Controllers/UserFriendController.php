<?php

namespace App\Http\Controllers;

use App\Models\UserFriend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserFriendController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $friends = DB::table('users')
            ->join('user_friends', function ($join) use ($user) {
                $join->on('users.id', '=', 'user_friends.friend_id')
                    ->where('user_friends.user_id', '=', $user->id)
                    ->where('user_friends.is_approved', '=', true)
                    ->orOn('users.id', '=', 'user_friends.user_id')
                    ->where('user_friends.friend_id', '=', $user->id)
                    ->where('user_friends.is_approved', '=', true);
            })
            ->where('users.id', '!=', $user->id)
            ->select('users.id', 'users.name', 'users.fullname', 'users.email', 'users.profile_picture')
            ->get();
            
        foreach ($friends as $friend) {
            $friend->profile_picture_url = $friend->profile_picture ? 
                Storage::url($friend->profile_picture) : null;
        }
        
        return response()->json($friends);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'friend_email' => 'required|email|exists:users,email',
        ]);
        
        $user = $request->user();
        $friend = User::where('email', $request->friend_email)->first();
        
        // Check if already friends or request pending
        $existingFriendship = UserFriend::where(function($query) use ($user, $friend) {
            $query->where('user_id', $user->id)
                ->where('friend_id', $friend->id);
        })->orWhere(function($query) use ($user, $friend) {
            $query->where('user_id', $friend->id)
                ->where('friend_id', $user->id);
        })->first();
        
        if ($existingFriendship) {
            return response()->json([
                'message' => 'Friend request already exists'
            ], 422);
        }
        
        UserFriend::create([
            'id' => Str::uuid(),
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'is_approved' => false,
        ]);
        
        return response()->json([
            'message' => 'Friend request sent'
        ], 201);
    }
}