<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class Users extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('view users')) {
            $roles = Role::latest()->get();
            return view('admin.user.index', compact('roles'));
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('create users')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'role' => 'required|exists:roles,id',
                'status' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            try {
                $role = Role::findById($request->role);

                $user = User::create([
                    'name' => $request->name,
                    'status' => $request->status,
                    'email' => $request->email,
                    'role_id' => $role->id,
                    'password' => Hash::make($request->password),
                ]);

                $user->assignRole($role);

                DB::commit();

                return response()->json([
                    'success' => 'User created successfully!'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'error' => 'Failed to create user: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'You are not authorized to create users'
            ], 403);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = User::with('roles')
                ->where('role_id', '!=', 85)
                ->where('name', '!=', 'LiveBits')
                ->latest()
                ->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $buttons = '';

                    if (\Auth::user()->can('edit users')) {
                        $buttons .= '<a href="javascript:void(0)" onclick="editUser(' . $row->id . ')" data-bs-toggle="modal" data-bs-target="#update" class="btn btn-primary btn-sm me-2"><i class="fa fa-edit"></i></a>';
                    }

                    if (\Auth::user()->can('delete users')) {
                        $buttons .= '<button type="button" class="btn btn-danger btn-sm" onclick="userDelete(' . $row->id . ')"><i class="fa fa-trash"></i></button>';
                    }

                    return $buttons;
                })
                ->addColumn('role', function ($row) {
                    return $row->getRoleNames()->first() ?? '-';
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 1) {
                        return '<span class="badge bg-success">Active</span>';
                    } else {
                        return '<span class="badge bg-danger">Inactive</span>';
                    }
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        if (\Auth::user()->can('edit users')) {
            $user = User::with('roles')->findOrFail($request->id);
            $userRole = $user->roles->first();

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->status
                ],
                'role_id' => $userRole ? $userRole->id : null
            ]);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if (\Auth::user()->can('edit users')) {
            $validator = Validator::make($request->all(), [
                'updateId' => 'required|exists:users,id',
                'updateName' => 'required|string|max:255',
                'updateEmail' => 'required|string|email|max:255|unique:users,email,' . $request->updateId,
                'updateRole' => 'required|exists:roles,id',
                'updateStatus' => 'required|in:0,1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();
            try {
                $user = User::findOrFail($request->updateId);
                $role = Role::findById($request->updateRole);

                $user->update([
                    'name' => $request->updateName,
                    'email' => $request->updateEmail,
                    'status' => $request->updateStatus,
                    'role_id' => $role->id,
                ]);

                // Sync roles
                $user->syncRoles([$role->id]);

                DB::commit();

                return response()->json([
                    'success' => 'User updated successfully!'
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'error' => 'Failed to update user: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'You are not authorized to update users'
            ], 403);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (\Auth::user()->can('delete users')) {
            try {
                $user = User::findOrFail($request->id);

                // Prevent deleting own account
                if ($user->id === Auth::id()) {
                    return response()->json([
                        'error' => 'You cannot delete your own account'
                    ], 400);
                }

                $userName = $user->name;
                $user->delete();

                return response()->json([
                    'success' => 'User ' . $userName . ' deleted successfully!'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Failed to delete user: ' . $e->getMessage()
                ], 500);
            }
        } else {
            return response()->json([
                'error' => 'You are not authorized to delete users'
            ], 403);
        }
    }
}
