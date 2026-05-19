<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
// use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;


class Permissions extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (\Auth::user()->can('view permissions')) {
            $permissions = Permission::all();
            return view('admin.permission.index', compact('permissions'));
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->can('create permissions')) {


            $request->validate([
                'name' => 'required|unique:permissions',
            ]);
            $request->validate([
                'name' => 'required|unique:permissions',
            ]);
            $permission = Permission::create(['name' => $request->name]);
            return response()->json(['success' => ' Permission created successfully' . ':' . $permission->name]);
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
        $data = Permission::all();
        
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $button = '';
                if (\Auth::user()->can('edit permissions')) {
                    $button .= '<a href="javascript:void(0)" onclick="edit(' . $row->id . ')" class="btn btn-primary btn-sm me-2" data-bs-toggle="modal" data-bs-target="#updateModel"><i class="fa fa-edit"></i></a>';
                }
                if (\Auth::user()->can('delete permissions')) {
                    $button .= '<button type="button" onclick="deleteds(' . $row->id . ')" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>';
                }
                return $button;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    
    return redirect()->route('admin.dashboard')->with('error', 'Invalid request!');
}

    /**
     * Display the specified resource.
     */
    // public function show(Request $request)
    // {
    //     if ($request->ajax()) {

    //         $data = Permission::all();
    //         return DataTables::of($data)
    //             ->addIndexColumn()
    //             ->addColumn('action', function ($row) {
    //                 $button = '<a href="javascript:void(0)" data-toggle="tooltip" data-id="' . $row->id . '" onclick="edit(' . $row->id . ')" data-original-title="Edit" class="edit btn btn-primary btn-sm edit-course" data-bs-toggle="modal" data-bs-target="#updateModel"><i class="fa fa-edit"></i></a>';
    //                 $button .= '<button type="button" data-toggle="tooltip"  data-id="' . $row->id . '" onclick="deleteds(' . $row->id . ')" data-original-title="Delete" class="btn btn-danger btn-sm delete-course"><i class="fa fa-trash"></i></button>';
    //                 return $button;
    //             })
    //             ->rawColumns(['action'])
    //             ->make(true);
    //     }
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        if (\Auth::user()->can('edit permissions')) {

            $permission = Permission::find($request->id);
            return response()->json($permission);
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        if (\Auth::user()->can('edit permissions')) {
            $permission = Permission::find($request->id);
            $permission->update(['name' => $request->name]);
            return response()->json(['success' => 'Permission updated successfully']);
        } else {
            return redirect()->route('admin.dashboard')->with('error', 'You are not authorized to access this page!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        if (\Auth::user()->can('delete permissions')) {

            $permission = Permission::find($request->id);
            $permission->delete();
            return response()->json(['success'=>'Permission deleted successfully']);
        } else {
            return  response()->json(['error'=> 'You are not authorized to access this page!']);
        }
    }   
}
