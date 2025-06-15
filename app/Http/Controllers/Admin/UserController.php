<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //
    public function index()
    {
        $users = User::with('books')->get();

        return view('admin.users.index', compact('users'));
    }

    public function update(Request $request, $id) 
    {
        $user = User::findOrFail($id);
        $user->update($request->only('username', 'first_name', 'last_name', 'email'));
   
        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy($id) 
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus!');
    }

}
