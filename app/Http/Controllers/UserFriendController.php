<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserFriend;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserFriendController extends Controller
{
    public function requestFriend(Request $request)
    {
        $request->validate([
            'friend_username' => 'required|exists:users,username',
        ]);

        $friend = User::where('username', $request->friend_username)->first();

        if ($friend->id === auth()->id()) {
            return response()->json(['message' => 'You cannot add yourself as a friend.'], 400);
        }

        $friendship = UserFriend::where('user_id', auth()->id())
            ->where('friend_id', $friend->id)
            ->first();

        if ($friendship) {
            return response()->json(['message' => 'Friend request already sent.'], 409);
        }

        UserFriend::create([
            'user_id' => auth()->id(),
            'friend_id' => $friend->id,
            'is_approved' => false,
        ]);

        return response()->json(['message' => 'Friend request sent.'], 201);
    }

    public function acceptFriendRequest($friendId)
    {
        $friendship = UserFriend::where('user_id', $friendId)
            ->where('friend_id', auth()->id())
            ->first();

        if (!$friendship) {
            return response()->json(['message' => 'Friend request not found.'], 404);
        }

        $friendship->is_approved = true;
        $friendship->save();

        return response()->json(['message' => 'Friend request accepted.'], 200);
    }

    public function rejectFriendRequest($friendId)
    {
        $friendship = UserFriend::where('user_id', $friendId)
            ->where('friend_id', auth()->id())
            ->first();

        if (!$friendship) {
            return response()->json(['message' => 'Friend request not found.'], 404);
        }

        $friendship->delete();

        return response()->json(['message' => 'Friend request rejected.'], 200);
    }

    public function showPendingRequests()
    {
        $pendingRequests = UserFriend::where('friend_id', auth()->id())
            ->where('is_approved', false)
            ->with('user')
            ->get();

        if ($pendingRequests->isEmpty()) {
            return response()->json(['message' => 'No pending friend requests.'], 404);
        }

        return response()->json($pendingRequests);
    }

    public function showAllFriends() {
        $userId = auth()->id();
        
        $friends = UserFriend::where(function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhere('friend_id', $userId);
            })
            ->where('is_approved', true)
            ->with(['user', 'friend'])
            ->get()
            ->map(function ($friendship) use ($userId) {
                return $friendship->user_id == $userId
                    ? $friendship->friend
                    : $friendship->user;
            });

        if ($friends->isEmpty()) {
            return response()->json(['message' => 'No friends found.'], 404);
        }

        return response()->json($friends);
    }

    public function getFriendById($friendId)
    {
        $friend = User::with(['books', 'friends', 'shelves', 'borrow', 'review'])->find($friendId);

        if (!$friend) {
            return response()->json(['message' => 'Friend not found.'], 404);
        }

        return response()->json($friend);
    }
}