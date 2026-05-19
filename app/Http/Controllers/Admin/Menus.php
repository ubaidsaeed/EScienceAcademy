<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\SubMenu;
use App\Models\SubChildMenu;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class Menus extends Controller
{
    protected $menuTypes = [
        'menu' => [
            'model' => Menu::class,
            'title' => 'Main Menu',
            'redirect' => 'menus.index',
            'parent' => null
        ],
        'submenu' => [
            'model' => SubMenu::class,
            'title' => 'Sub Menu',
            'redirect' => 'menus.submenu',
            'parent' => 'menu_id'
        ],
        'subchild' => [
            'model' => SubChildMenu::class,
            'title' => 'Sub Child Menu',
            'redirect' => 'menus.subchild',
            'parent' => ['menu_id', 'menu_child_id']
        ],
    ];

    /**
     * Display main menu listing
     */
    public function index(Request $request)
    {
        if (!auth()->user()->can('view header menu')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
    }
        if ($request->ajax()) {
            return $this->getMenuData();
        }

        $pageData = Page::where('status', 'active')->select('id', 'title')->get();
        $currentView = 'menu';

        return view('admin.menus.index', compact('pageData', 'currentView'));
    }

    /**
     * Display submenu listing
     */
    public function submenu(Request $request, $menuId)
    {
        if ($request->ajax()) {
            return $this->getSubMenuData($menuId);
        }

        $menu = Menu::findOrFail($menuId);
        $pageData = Page::where('status', 'active')->select('id', 'title')->get();
        $currentView = 'submenu';

        return view('admin.menus.index', compact('menu', 'pageData', 'menuId', 'currentView'));
    }

    /**
     * Display subchild menu listing
     */
    public function subchild(Request $request, $menuId, $submenuId)
    {
        if ($request->ajax()) {
            return $this->getSubChildData($menuId, $submenuId);
        }

        $submenu = SubMenu::findOrFail($submenuId);
        $pageData = Page::where('status', 'active')->select('id', 'title')->get();
        $currentView = 'subchild';

        return view('admin.menus.index', compact('submenu', 'pageData', 'menuId', 'submenuId', 'currentView'));
    }

    /**
     * Get main menu data for DataTable
     */
    private function getMenuData()
    {
        try {
            $query = Menu::query()->with('page');

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="btn-group">';
                    if (auth()->user()->can('edit header menu')) {
                    $actionBtn .= '<a href="' . route('menus.submenu', $row->id) . '" 
                                    class="btn btn-sm btn-info" title="View Sub Menus">
                                    <i class="fa fa-list"></i>
                                  </a>';
                    }
                    if (auth()->user()->can('edit header menu')) {
                    $actionBtn .= '<a href="' . route('menus.edit', ['id' => $row->id, 'type' => 'menu']) . '" 
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>';
                    }
                    if (auth()->user()->can('delete header menu')) {
                    $actionBtn .= '<button type="button" 
                                    class="btn btn-sm btn-danger delete-btn" 
                                    data-id="' . $row->id . '" 
                                    data-type="menu"
                                    data-name="' . $row->name . '"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                  </button>';
                    }
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->addColumn('page_link', function ($row) {
                    if ($row->page) {
                        return $row->page->title;
                    } elseif ($row->link) {
                        return '<span class="text-muted">Custom: </span>' . $row->link;
                    }
                    return '-';
                })
                ->addColumn('status_badge', function ($row) {
                    $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('target_window_badge', function ($row) {
                    $badgeClass = $row->target_window === '_self' ? 'badge bg-info' : 'badge bg-warning';
                    $text = $row->target_window === '_self' ? 'Self' : 'New Tab';
                    return '<span class="' . $badgeClass . '">' . $text . '</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
                })
                ->rawColumns(['action', 'page_link', 'status_badge', 'target_window_badge'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => request()->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get submenu data for DataTable
     */
    private function getSubMenuData($menuId)
    {
        try {
            $query = SubMenu::where('menu_id', $menuId)->with(['page', 'menu']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($menuId) {
                    $actionBtn = '<div class="btn-group">';
                    $actionBtn .= '<a href="' . route('menus.subchild', ['menuId' => $menuId, 'submenuId' => $row->id]) . '" 
                                    class="btn btn-sm btn-info" title="View Sub Child">
                                    <i class="fa fa-sitemap"></i>
                                  </a>';
                    $actionBtn .= '<a href="' . route('menus.edit', [
                        'id' => $row->id,
                        'type' => 'submenu',
                        'menu_id' => $menuId
                    ]) . '" 
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>';
                    $actionBtn .= '<button type="button" 
                                    class="btn btn-sm btn-danger delete-btn" 
                                    data-id="' . $row->id . '" 
                                    data-type="submenu"
                                    data-name="' . $row->name . '"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                  </button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->addColumn('page_link', function ($row) {
                    if ($row->page) {
                        return $row->page->title;
                    } elseif ($row->link) {
                        return '<span class="text-muted">Custom: </span>' . $row->link;
                    }
                    return '-';
                })
                ->addColumn('status_badge', function ($row) {
                    $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('target_window_badge', function ($row) {
                    $badgeClass = $row->target_window === '_self' ? 'badge bg-info' : 'badge bg-warning';
                    $text = $row->target_window === '_self' ? 'Self' : 'New Tab';
                    return '<span class="' . $badgeClass . '">' . $text . '</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
                })
                ->rawColumns(['action', 'page_link', 'status_badge', 'target_window_badge'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => request()->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Get subchild data for DataTable
     */
    private function getSubChildData($menuId, $submenuId)
    {
        try {
            $query = SubChildMenu::where('menu_id', $menuId)
                ->where('menu_child_id', $submenuId)
                ->with(['page', 'submenu']);

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($menuId, $submenuId) {
                    $actionBtn = '<div class="btn-group">';
                    $actionBtn .= '<a href="' . route('menus.edit', [
                        'id' => $row->id,
                        'type' => 'subchild',
                        'menu_id' => $menuId,
                        'submenu_id' => $submenuId
                    ]) . '" 
                                    class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fa fa-edit"></i>
                                  </a>';
                    $actionBtn .= '<button type="button" 
                                    class="btn btn-sm btn-danger delete-btn" 
                                    data-id="' . $row->id . '" 
                                    data-type="subchild"
                                    data-name="' . $row->name . '"
                                    title="Delete">
                                    <i class="fa fa-trash"></i>
                                  </button>';
                    $actionBtn .= '</div>';
                    return $actionBtn;
                })
                ->addColumn('page_link', function ($row) {
                    if ($row->page) {
                        return $row->page->title;
                    } elseif ($row->link) {
                        return '<span class="text-muted">Custom: </span>' . $row->link;
                    }
                    return '-';
                })
                ->addColumn('status_badge', function ($row) {
                    $badgeClass = $row->status === 'active' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('target_window_badge', function ($row) {
                    $badgeClass = $row->target_window === '_self' ? 'badge bg-info' : 'badge bg-warning';
                    $text = $row->target_window === '_self' ? 'Self' : 'New Tab';
                    return '<span class="' . $badgeClass . '">' . $text . '</span>';
                })
                ->addColumn('created_at_formatted', function ($row) {
                    return $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : '-';
                })
                ->rawColumns(['action', 'page_link', 'status_badge', 'target_window_badge'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => request()->draw,
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Show create form
     */
    public function create(Request $request)
    {
         if (!auth()->user()->can('create header menu')) {
          return redirect()
            ->back()
            ->with('error', 'Permission denied');
          }
        $type = $request->query('type', 'menu');
        $menuId = $request->query('menu_id');
        $submenuId = $request->query('submenu_id');

        if (!isset($this->menuTypes[$type])) {
            abort(404);
        }

        $pageData = Page::where('status', 'active')->select('id', 'title')->get();
        $currentView = $type;

        // For edit, $record will be null
        $record = null;

        return view('admin.menus.form', compact(
            'pageData',
            'type',
            'menuId',
            'submenuId',
            'currentView',
            'record'
        ));
    }

    /**
     * Show edit form
     */
    public function edit($id, Request $request)
    {
         if (!auth()->user()->can('edit header menu')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
    }
        $type = $request->query('type', 'menu');
        $menuId = $request->query('menu_id');
        $submenuId = $request->query('submenu_id');

        if (!isset($this->menuTypes[$type])) {
            abort(404);
        }

        $modelClass = $this->menuTypes[$type]['model'];
        $record = $modelClass::findOrFail($id);

        $pageData = Page::where('status', 'active')->select('id', 'title')->get();
        $currentView = $type;

        return view('admin.menus.form', compact('record', 'pageData', 'type', 'menuId', 'submenuId', 'currentView'));
    }

    /**
     * Unified store/update method for all menu types
     */
    public function storeUpdate(Request $request)
    {
        $type = $request->input('type', 'menu');
        $id = $request->input('id');

        if (!isset($this->menuTypes[$type])) {
            abort(404);
        }
        // Common validation rules
        $validationRules = [
            'name' => 'required|string|max:255',
            'priority' => 'required|integer',
            'link' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'target_window' => 'required|in:_blank,_self',
            'page_id' => 'nullable|integer|exists:pages,id',
        ];

        $request->validate($validationRules);

        try {
            DB::beginTransaction();

            $modelClass = $this->menuTypes[$type]['model'];

            // Prepare data based on type
            $data = [
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'page_id' => $request->page_id ?: null,
                'priority' => $request->priority,
                'link' => $request->link ?? '',
                'status' => $request->status,
                'target_window' => $request->target_window,
            ];
            // dd($data);

            // Add parent relationships
            if ($type === 'submenu') {
                $data['menu_id'] = $request->menu_id;
            } elseif ($type === 'subchild') {
                $data['menu_id'] = $request->menu_id;
                $data['menu_child_id'] = $request->submenu_id;
            }

            // Create or update
            if ($id) {
                $record = $modelClass::findOrFail($id);
                $record->update($data);
                $message = $this->menuTypes[$type]['title'] . ' updated successfully';
            } else {
                // dd($data);
                $modelClass::create($data);
                $message = $this->menuTypes[$type]['title'] . ' created successfully';
            }

            DB::commit();

            // Redirect based on type
            return $this->redirectAfterOperation($type, $request, $message);
        } catch (ValidationException $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'An error occurred: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete menu item
     */
    public function destroy(Request $request)
    {
         if (!auth()->user()->can('delete header menu')) {
        return redirect()
            ->back()
            ->with('error', 'Permission denied');
    }
        try {
            $type = $request->input('type', 'menu');
            $id = $request->input('id');
            
            if (!isset($this->menuTypes[$type])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid menu type'
                ], 400);
            }

            $modelClass = $this->menuTypes[$type]['model'];
            
            // Find the record
            $record = $modelClass::find($id);
            
            if (!$record) {
                return response()->json([
                    'success' => false,
                    'message' => ucfirst($type) . ' not found'
                ], 404);
            }

            DB::beginTransaction();

            try {
                // Cascade delete for main menu
                if ($type === 'menu') {
                    // Delete all submenus of this menu
                    SubMenu::where('menu_id', $id)->delete();
                    // Delete all subchild menus of this menu
                    SubChildMenu::where('menu_id', $id)->delete();
                } 
                // Cascade delete for submenu
                elseif ($type === 'submenu') {
                    // Delete all subchild menus of this submenu
                    SubChildMenu::where('menu_child_id', $id)->delete();
                }
                
                // Delete the record
                $record->delete();
                
                DB::commit();
                
                return response()->json([
                    'success' => true,
                    'message' => ucfirst($type) . ' deleted successfully'
                ]);
                
            } catch (\Exception $e) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting ' . $type . ': ' . $e->getMessage()
                ], 500);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Redirect after store/update operation
     */
    private function redirectAfterOperation($type, $request, $message)
    {
        $redirectRoute = $this->menuTypes[$type]['redirect'];

        if ($type === 'menu') {
            return redirect()->route($redirectRoute)
                ->with('success', $message);
        } elseif ($type === 'submenu') {
            return redirect()->route($redirectRoute, ['menuId' => $request->menu_id])
                ->with('success', $message);
        } elseif ($type === 'subchild') {
            return redirect()->route($redirectRoute, [
                'menuId' => $request->menu_id,
                'submenuId' => $request->submenu_id
            ])
                ->with('success', $message);
        }
    }
}
