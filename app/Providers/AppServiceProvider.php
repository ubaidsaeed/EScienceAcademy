<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    private $permissionTypes = ['view', 'create', 'edit', 'delete','show'];
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share modules with all views that need them
        View::composer(['admin.role.index'], function ($view) {
            $modules = $this->getModulesWithPermissions();
            $view->with('modules', $modules)
                 ->with('permissionTypes', $this->permissionTypes);
        });
    }
    
    /**
     * Get modules with their permissions
     */
    private function getModulesWithPermissions()
    {
        $permissions = Permission::all();
        $modules = [];
        
        foreach ($permissions as $permission) {
            $name = $permission->name;
            
            foreach ($this->permissionTypes as $type) {
                if (str_starts_with($name, $type . ' ')) {
                    $moduleName = str_replace($type . ' ', '', $name);
                    
                    if (!isset($modules[$moduleName])) {
                        $modules[$moduleName] = [
                            'name' => $moduleName,
                            'display_name' => ucwords(str_replace(['_', '-'], ' ', $moduleName)),
                            'permissions' => []
                        ];
                    }
                    
                    $modules[$moduleName]['permissions'][$type] = [
                        'id' => $permission->id,
                        'name' => $permission->name
                    ];
                    break;
                }
            }
        }
        
        uasort($modules, function($a, $b) {
            return strcmp($a['display_name'], $b['display_name']);
        });
        
        return $modules;
    }
    
}