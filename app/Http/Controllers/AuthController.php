<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function view()
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'unique_code' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            $result = $this->authService->login($validated);
            Session::put('user', $result['user']);
            return redirect()->route('pages.home')->with('success', 'Login berhasil!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors([
                'login_error' => 'Kode unik atau password salah.'
            ])->withInput();
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = $request->user();
        $result = $this->authService->logout($user);
        return redirect()->route('login')->with('success', 'Anda telah logout.');
    }
}