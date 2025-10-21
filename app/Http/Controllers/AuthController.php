<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
            
            session(['token' => $result['token']]);
            session(['user' => $result['user']]);

            Auth::loginUsingId($result['user']['id']);

            return redirect()->route('home')->with('success', 'Login berhasil!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors([
                'login_error' => 'Kode atau kata sandi salah!',
            ])->withInput();
        } catch (\Exception $e) {
            return back()->withErrors([
                'login_error' => 'Terjadi kesalahan saat login.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        
        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }
}