<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Display all users with search & filter
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search by name, email, phone, or department
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%");
            });
        }
        
        // Filter by role
        if ($request->filled('role')) {
            $query->where('user_type', $request->role);
        }
        
        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $users = $query->latest()->paginate(15)->appends($request->query());
        $departments = Department::where('is_active', true)->orderBy('code')->get();
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();

        return view('admin.users.index', compact('users', 'departments', 'totalUsers', 'activeUsers'));
    }

    /**
     * Show create user form
     */
    public function create()
    {
        $departments = Department::where('is_active', true)->orderBy('code')->get();
        return view('admin.users.create', compact('departments'));
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|min:8|confirmed',
            'user_type' => 'required|in:admin,hr_manager,evaluator,applicant',
            'department' => 'nullable|string|max:100',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'department' => $request->department,
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Assign role
        $roleName = match($request->user_type) {
            'admin' => 'admin',
            'hr_manager' => 'hr_manager',
            'evaluator' => 'evaluator',
            default => 'applicant',
        };
        
        if (Role::where('name', $roleName)->exists()) {
            $user->assignRole($roleName);
        }

        return redirect()->route('admin.users.index')
            ->with('success', '✅ User created successfully!');
    }

    /**
     * Show edit user form
     */
    public function edit(User $user)
    {
        $departments = Department::where('is_active', true)->orderBy('code')->get();
        return view('admin.users.edit', compact('user', 'departments'));
    }

    /**
     * Update user
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $user->id,
            'user_type' => 'required|in:admin,hr_manager,evaluator,applicant',
            'department' => 'nullable|string|max:100',
            'status' => 'required|in:active,suspended',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'user_type' => $request->user_type,
            'department' => $request->department,
            'status' => $request->status,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed',
            ]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sync role
        $roleName = match($request->user_type) {
            'admin' => 'admin',
            'hr_manager' => 'hr_manager',
            'evaluator' => 'evaluator',
            default => 'applicant',
        };
        $user->syncRoles([$roleName]);

        return redirect()->route('admin.users.index')
            ->with('success', '✅ User updated successfully!');
    }

    /**
     * Toggle user status (Active/Suspended)
     */
    public function toggleStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', '❌ You cannot change your own status.');
        }
        
        $user->status = $user->status === 'active' ? 'suspended' : 'active';
        $user->save();
        
        return back()->with('success', '✅ User status changed to ' . $user->status . '.');
    }

    /**
     * Delete user
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', '❌ You cannot delete your own account.');
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', '✅ User deleted successfully!');
    }
}
