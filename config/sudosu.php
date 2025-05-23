<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Allowed TLDs - Use with caution!
    |--------------------------------------------------------------------------
    |
    | This is to prevent mis-usage during production if debug mode is
    | unintentionally left active. The package will detect the site
    | URL and if the TLD isn't present in this array, it will not
    | activate. If your development TLD is different to .dev or
    | .local, simply add it to the arrow below.
    |
     */
    
   // 允许使用的顶级域名
    'allowed_tlds' => ['dev', 'local', 'xin'],

    // 用户模型
    'user_model' => App\Models\User::class
    
];