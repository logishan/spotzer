<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'departments'])->get();
        return view('users.management.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('level')->get();
        $departments = Department::all();
        return view('users.management.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
            'department_id' => 'required|exists:departments,id',
            'password' => 'nullable|min:8|confirmed',
            'status' => 'required|in:active,inactive',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->status = $validated['status'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update roles
        $user->roles()->sync($validated['role_ids']);
        
        // Update department
        $user->departments()->sync([$validated['department_id']]);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
} 