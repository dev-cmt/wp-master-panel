<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ImageHelper;

class UserController extends Controller
{
    // List users
    public function index()
    {
        $users = User::with('roles')->latest()->paginate(10);
        $roles = Role::all();
        return view('backend.users.index', compact('users', 'roles'));
    }

    // Update user
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->id,
            'role' => 'required|exists:roles,name',
            'photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        $user = User::findOrFail($request->id);

        // Upload new photo if provided
        if ($request->hasFile('photo')) {
            $photoPath = ImageHelper::uploadImage($request->file('photo'), 'uploads/profile', $user->photo_path);
            $user->photo_path = $photoPath;
        }

        // Update basic info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        // Sync role
        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    // Delete user
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Delete photo if exists
        ImageHelper::deleteImage($user->photo_path);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
