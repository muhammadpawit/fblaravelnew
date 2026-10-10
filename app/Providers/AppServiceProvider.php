<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View as BladeView;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (array_keys(config('access.permissions')) as $permission) {
            Gate::define($permission, fn (User $user): bool => $user->hasPermission($permission));
        }

        View::composer('components.layout', function (BladeView $view): void {
            $user = auth()->user();
            $menus = Menu::query()->with(['permission', 'children' => function ($query) {
                $query->with(['permission', 'children' => function ($query) {
                    $query->with('permission')->where('is_active', true)->orderBy('sort_order')->orderBy('id');
                }])->where('is_active', true)->orderBy('sort_order')->orderBy('id');
            }])->where('is_active', true)->whereNull('parent_id')
                ->orderBy('sort_order')->orderBy('id')->get();

            // Filter menus based on permissions recursively
            $filterMenus = function ($items) use (&$filterMenus, $user) {
                return $items->filter(function (Menu $menu) use ($filterMenus, $user): bool {
                    $definition = config('access.menu_routes')[$menu->route_name] ?? null;
                    $canAccess = false;
                    
                    if ($menu->route_name) {
                        $canAccess = $user !== null && $definition !== null
                            && ($definition['permission'] === null || $user->can($definition['permission']));
                    } else {
                        // For parent menus without route, they can access if they have any accessible child
                        $canAccess = true;
                    }

                    if ($canAccess && $menu->relationLoaded('children') && $menu->children->isNotEmpty()) {
                        $menu->setRelation('children', $filterMenus($menu->children));
                        // If all children were filtered out and it has no route, maybe hide it?
                        // But let's just keep it simple.
                        if (!$menu->route_name && $menu->children->isEmpty()) {
                            return false;
                        }
                    }

                    return $canAccess;
                });
            };

            $view->with('navigationMenus', $filterMenus($menus)->groupBy('group'));
        });
    }
}
