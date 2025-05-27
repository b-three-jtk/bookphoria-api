<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Validation\ValidationException;
use App\Notifications\ResetPasswordNotification;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|min:8'
        ]);

        $user = User::create([
            'username' => $registerUserData['username'],
            'email' => $registerUserData['email'],
            'password' => Hash::make($registerUserData['password']),
        ]);

        $token = $user->createToken($user->username . '-AuthToken')->plainTextToken;

        return response()->json([
            'accessToken' => $token,
            'tokenType' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'book_count' => 0,
                'reading_list_count' => 0,
                'friend_count' => 0
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|min:8'
        ]);

        $user = User::where('email', $loginUserData['email'])->first();

        if (!$user || !Hash::check($loginUserData['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken($user->name . '-AuthToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'book_count' => $user->books()->count(),
                'reading_list_count' => $user->shelves()->count(),
                'friend_count' => $user->friends()->count()
            ]
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Kirim link reset password
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'message' => 'Link reset password telah dikirim ke email Anda.'
            ], 200);
        }

        // Jika email tidak ditemukan
        return response()->json([
            'message' => 'Tidak dapat menemukan pengguna dengan email tersebut.'
        ], 422);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Reset password
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'Password berhasil diubah'
            ], 200);
        }

        return response()->json([
            'message' => 'Link tidak valid atau sudah kadaluarsa'
        ], 422);
    }

    public function editProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'username' => 'required|string|max:20|unique:users,username,' . $user->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $data = $request->only(['username', 'first_name', 'last_name', 'email']);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            ]
        ]);
    }

    public function getUserByUsername($username)
    {
        $user = User::with(['books', 'friends', 'shelves', 'borrow', 'review'])
            ->where('username', $username)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'book_count' => $user->books()->count(),
            'reading_list_count' => $user->shelves()->count(),
            'friend_count' => $user->friends()->count(),
            'books' => $user->books,
            'friends' => $user->friends,
            'shelves' => $user->shelves
        ]);
    }

    public function getCurrentUser(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'profile_picture' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'book_count' => $user->books()->count(),
            'reading_list_count' => $user->shelves()->count(),
            'friend_count' => $user->friends()->count()
        ]);
    }
}
