<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function attemptLogin(Request $request)
    {
        try {
            $credentials = $this->validateCredentials($request);
            
            $field = $this->determineLoginField($credentials['login']);
            
            return $this->guard()->attempt(
                [$field => $credentials['login'], 'password' => $credentials['password']],
                $request->filled('remember')
            );
        } catch (\Exception $e) {
            Log::error('Login attempt failed: '.$e->getMessage());
            throw ValidationException::withMessages([
                'login' => [trans('auth.failed')],
            ]);
        }
    }

    protected function validateCredentials(Request $request): array
    {
        return $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);
    }

    protected function determineLoginField(string $login): string
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        }
        
        if (preg_match('/^1[3-9]\d{9}$/', $login)) {
            return 'phone';
        }
        
        return 'username';
    }
}