protected function attemptLogin(Request $request)
{
    $credentials = $request->only('login', 'password');

    // 判断登录方式
    $field = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    if (preg_match('/^1[3-9]\d{9}$/', $credentials['login'])) {
        $field = 'phone';
    }

    // 尝试登录
    if ($this->guard()->attempt(
        [$field => $credentials['login'], 'password' => $credentials['password']], $request->filled('remember')
    )) {
        return true;
    }

    // 登录失败，记录日志
    \Log::warning("Login failed for: " . $credentials['login']);
    return false;
}