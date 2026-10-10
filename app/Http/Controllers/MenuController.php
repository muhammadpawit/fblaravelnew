<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveMenuRequest;
use App\Http\Requests\SearchMasterRequest;
use App\Models\Menu;
use App\Services\AccessControlService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(SearchMasterRequest $request): View
    {

        $menus = Menu::query()->with('permission')->orderBy('sort_order')->orderBy('id')->paginate(20);

        return view('access.menus.index', ['menus' => $menus]);
    }

    public function create(): View
    {
        return view('access.menus.form', [
            'menu' => new Menu, 
            'destinations' => config('access.menu_routes'),
            'parents' => Menu::whereNull('parent_id')->orderBy('label')->get()
        ]);
    }

    public function store(SaveMenuRequest $request, AccessControlService $service): RedirectResponse
    {
        $service->saveMenu(new Menu, $request->validated());

        return to_route('access.menus.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit(Menu $menu): View
    {
        return view('access.menus.form', [
            'menu' => $menu, 
            'destinations' => config('access.menu_routes'),
            'parents' => Menu::whereNull('parent_id')->where('id', '!=', $menu->id)->orderBy('label')->get()
        ]);
    }

    public function update(SaveMenuRequest $request, Menu $menu, AccessControlService $service): RedirectResponse
    {
        $service->saveMenu($menu, $request->validated());

        return to_route('access.menus.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(Request $request, Menu $menu, AccessControlService $service): RedirectResponse
    {
        $menu->delete();

        return to_route('access.menus.index')->with('success', 'Data berhasil dihapus.');
    }
}
