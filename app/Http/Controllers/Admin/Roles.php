<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class Roles extends Controller
{
    private $permissionTypes = ['view', 'create', 'edit', 'delete','show'];
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('view roles')) {
            $roles = Role::latest()->get();
            
            // Get all permissions from database
            $permissions = Permission::all();
            
            // Get unique modules from permissions
            $modules = $this->extractModulesFromPermissions($permissions);
            
            // Get permission types for the view
            $permissionTypes = $this->permissionTypes;
            
            return view("admin.role.index", compact(
                'roles', 
                'permissions',
                'modules',
                'permissionTypes'
            ));
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }
    }
    
    /**
     * Extract unique modules from permissions
     */
    private function extractModulesFromPermissions($permissions)
    {
        $modules = [];
        
        foreach ($permissions as $permission) {
            $name = $permission->name;
            
            // Remove permission type prefix
            foreach ($this->permissionTypes as $type) {
                if (str_starts_with($name, $type . ' ')) {
                    $moduleName = str_replace($type . ' ', '', $name);
                    
                    // Ensure module name is a string
                    $moduleName = trim((string)$moduleName);
                    
                    if (!isset($modules[$moduleName])) {
                        $modules[$moduleName] = [
                            'name' => $moduleName,
                            'display_name' => ucwords(str_replace(['_', '-'], ' ', $moduleName)),
                            'permissions' => []
                        ];
                    }
                    
                    // Add permission to the module
                    $modules[$moduleName]['permissions'][$type] = [
                        'id' => $permission->id,
                        'name' => $permission->name
                    ];
                    break;
                }
            }
        }
        
        // Convert to simple indexed array and sort
        $modulesArray = array_values($modules);
        usort($modulesArray, function($a, $b) {
            return strcmp($a['display_name'], $b['display_name']);
        });
        
        return $modulesArray;
    }
    
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('create roles')) {
            $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles,name',
                'dash' => 'required|array|min:1'
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $role = Role::create([
                'name' => $request->name,
                'created_by' => Auth::id(),
            ]);

            $permissions = $request->dash;
            foreach ($permissions as $permissionId) {
                $permission = Permission::find($permissionId);
                if ($permission) {
                    $role->givePermissionTo($permission);
                }
            }

            return redirect()->route('user.roles')->with('success', 'Role created successfully');
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::latest()->get();
            
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $button = '';
                    if (\Auth::user()->can('edit roles')) {
                        $button .= '<a href="javascript:void(0)" onclick="editRole(' . $row->id . ')" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#update"><i class="fa fa-edit"></i></a>';
                    }
                    if (\Auth::user()->can('delete roles')) {
                        $button .= '<button type="button" onclick="deleteRole(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                    }
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
     public function edit(Request $request)
    {
        if (\Auth::user()->can('edit roles')) { // Note: singular 'role' not 'roles'
            $role = Role::with('permissions')->findOrFail($request->id);
            
            return response()->json([
                'role' => [
                    'id' => $role->id,
                    'name' => $role->name
                ],
                'permission' => $role->permissions->pluck('id')->toArray()
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
        if (\Auth::user()->can('edit roles')) {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:100|unique:roles,name,' . $request->editid,
                'permission' => 'required|array|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            // Find and update the role
            $role = Role::findOrFail($request->editid);
            $role->name = $request->name;
            $role->save();

            // Sync permissions
            $permissions = Permission::whereIn('id', $request->permission)->get();
            $role->syncPermissions($permissions);

            return response()->json([
                'success' => 'Role ' . $role->name . ' updated successfully!'
            ]);
        } else {
            return response()->json([
                'error' => 'You are not authorized to update roles'
            ], 403);
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if (\Auth::user()->can('delete roles')) {
            $role = Role::findOrFail($request->id);
            
            // Check if role has users assigned
            if ($role->users()->count() > 0) {
                return response()->json([
                    'error' => 'Cannot delete role. There are users assigned to this role.'
                ], 400);
            }
            
            $role->delete();
            
            return response()->json(['success' => 'Role deleted successfully!']);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    }
}