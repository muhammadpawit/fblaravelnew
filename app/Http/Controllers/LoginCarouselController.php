<?php

namespace App\Http\Controllers;

use App\Models\LoginCarousel;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LoginCarouselController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->query('q', '');
        $carousels = LoginCarousel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', '%'.$search.'%')
                      ->orWhere('subtitle', 'like', '%'.$search.'%');
            })
            ->orderBy('order')->orderBy('id')->paginate(15)->withQueryString();

        return view('master.login-carousels.index', compact('carousels', 'search'));
    }

    public function create(): View
    {
        return view('master.login-carousels.form', ['carousel' => new LoginCarousel]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|url|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);
        $validated['is_active'] = $request->has('is_active');

        LoginCarousel::create($validated);

        return to_route('master.login-carousels.index')->with('success', 'Carousel berhasil ditambahkan.');
    }

    public function edit(LoginCarousel $loginCarousel): View
    {
        return view('master.login-carousels.form', ['carousel' => $loginCarousel]);
    }

    public function update(Request $request, LoginCarousel $loginCarousel): RedirectResponse
    {
        $validated = $request->validate([
            'image' => 'required|url|max:255',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);
        $validated['is_active'] = $request->has('is_active');

        $loginCarousel->update($validated);

        return to_route('master.login-carousels.index')->with('success', 'Carousel berhasil diperbarui.');
    }

    public function destroy(LoginCarousel $loginCarousel): RedirectResponse
    {
        $loginCarousel->delete();

        return to_route('master.login-carousels.index')->with('success', 'Carousel berhasil dihapus.');
    }
}
