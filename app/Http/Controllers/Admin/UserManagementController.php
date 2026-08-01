<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }
        
        if ($request->filled('role')) {
            $query->where('user_type', $request->role);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $users = $query->latest()->paginate(15);
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $adminCount = User::where('user_type', 'admin')->count();
        $applicantCount = User::where('user_type', 'applicant')->count();
        
        return view('admin.users.index', compact('users', 'totalUsers', 'activeUsers', 'adminCount', 'applicantCount'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required|in:admin,hr_manager,evaluator,applicant',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        $roleName = match($request->user_type) {
            'admin' => 'admin',
            'hr_manager' => 'hr_manager',
            'evaluator' => 'evaluator',
            default => 'applicant',
        };
        
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        return redirect()->route('admin.users.index')->with('success', 'User created successfully!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|string|unique:users,phone,'.$user->id,
            'user_type' => 'required|in:admin,hr_manager,evaluator,applicant',
            'status' => 'required|in:active,suspended',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_type' => $request->user_type,
            'status' => $request->status,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $user->update(['password' => Hash::make($request->password)]);
        }

        $roleName = match($request->user_type) {
            'admin' => 'admin',
            'hr_manager' => 'hr_manager',
            'evaluator' => 'evaluator',
            default => 'applicant',
        };
        $user->syncRoles([$roleName]);

        return redirect()->route('admin.users.index')->with('success', 'User updated!');
    }

    public function toggleStatus(User $user)
    {
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();
        return back()->with('success', 'Status updated to '.$user->status);
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete yourself.');
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }
}
