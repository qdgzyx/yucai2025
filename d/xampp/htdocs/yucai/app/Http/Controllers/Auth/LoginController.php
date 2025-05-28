protected function attemptLogin(Request $request)
{
    $credentials = $request->only('login', 'password');

    // 仅支持邮箱登录
    $field = 'email';

    // 尝试登录
    return $this->guard()->attempt(
        [$field => $credentials['login'], 'password' => $credentials['password']], $request->filled('remember')
    );
}
