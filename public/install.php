<?php
// 1. 环境验证（增强检查）
$errors = [];
// 更新PHP版本要求到8.1+
if (version_compare(PHP_VERSION, '8.1.0') < 0) {
    $errors[] = "PHP 版本需 ≥ 8.1（当前版本：" . PHP_VERSION . "）";
}
// 添加Laravel必需的扩展检查
$requiredExts = ['mbstring', 'openssl', 'pdo', 'tokenizer', 'xml', 'fileinfo', 'ctype'];
foreach ($requiredExts as $ext) {
    if (!extension_loaded($ext)) {
        $errors[] = "缺少必需扩展：$ext";
    }
}

// // 添加存储目录权限检查（新增）
// $writableDirs = ['.', 'storage', 'bootstrap/cache'];
// foreach ($writableDirs as $dir) {
//     if (!is_writable($dir)) {
//         $errors[] = "目录无写入权限：$dir";
//     }
// }

if (file_exists('.env')) {
    die("安装已完成！如需重新安装，请先删除 .env 文件");
}

// 2. 显示错误或进入安装表单
if (!empty($errors)) {
    die("
    <div style='max-width:800px; margin:2rem auto; padding:0 1rem'>
        <div style='background:#fff3f3; padding:2rem; border-radius:8px; border-left:4px solid #ff4d4d; box-shadow:0 2px 4px rgba(0,0,0,0.1)'>
            <h3 style='color:#ff4d4d; margin:0 0 1rem; display:flex; align-items:center; gap:0.5rem'>
                <svg style='width:24px; height:24px' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>
                环境检查未通过
            </h3>
            <div style='background:white; padding:1rem; border-radius:4px'>
                <div style='color:#666; margin-bottom:1rem'>请解决以下问题后刷新页面：</div>
                <ul style='list-style:none; padding:0; margin:0; color:#444'>
                    " . implode("", array_map(function($error) {
                        $solution = "";
                        if (strpos($error, 'PHP 版本') !== false) {
                            $solution = "<div style='font-size:0.9em; color:#666; margin-top:4px'>解决方案：联系主机商升级PHP版本或使用Docker环境</div>";
                        } elseif (strpos($error, '目录无写入权限') !== false) {
                            $solution = "<div style='font-size:0.9em; color:#666; margin-top:4px'>解决方案：运行 <code style='background:#f3f3f3; padding:2px 4px; border-radius:2px'>chmod -R 775 {$dir}</code></div>";
                        }
                        return "<li style='padding:0.5rem 0; border-bottom:1px solid #eee'>• {$error}{$solution}</li>";
                    }, $errors)) . "
                </ul>
            </div>
        </div>
    </div>");
}

// 保留修改后的错误处理逻辑（使用正则提取目录名）
if (!empty($errors)) {
    die("
    <div style='max-width:800px; margin:2rem auto; padding:0 1rem'>
        <div style='background:#fff3f3; padding:2rem; border-radius:8px; border-left:4px solid #ff4d4d; box-shadow:0 2px 4px rgba(0,0,0,0.1)'>
            <h3 style='color:#ff4d4d; margin:0 0 1rem; display:flex; align-items:center; gap:0.5rem'>
                <svg style='width:24px; height:24px' fill='none' stroke='currentColor' viewBox='0 0 24 24'><path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'></path></svg>
                环境检查未通过
            </h3>
            <div style='background:white; padding:1rem; border-radius:4px'>
                <div style='color:#666; margin-bottom:1rem'>请解决以下问题后刷新页面：</div>
                <ul style='list-style:none; padding:0; margin:0; color:#444'>
                    " . implode("", array_map(function($error) use ($writableDirs) {
                        $solution = "";
                        if (strpos($error, 'PHP 版本') !== false) {
                            $solution = "<div style='font-size:0.9em; color:#666; margin-top:4px'>解决方案：联系主机商升级PHP版本或使用Docker环境</div>";
                        } elseif (strpos($error, '目录无写入权限') !== false) {
                            // 新增正则表达式匹配目录名称
                            preg_match('/目录无写入权限：(.*)/', $error, $matches);
                            $dir = $matches[1] ?? '';
                            $solution = $dir ? "<div style='font-size:0.9em; color:#666; margin-top:4px'>解决方案：运行 <code style='background:#f3f3f3; padding:2px 4px; border-radius:2px'>chmod -R 775 $dir</code></div>" : "";
                        }
                        return "<li style='padding:0.5rem 0; border-bottom:1px solid #eee'>• {$error}{$solution}</li>";
                    }, $errors)) . "
                </ul>
            </div>
        </div>
    </div>");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 3. 增强数据库配置验证（新增）
    try {
        $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']};charset=utf8mb4";
        $db = new PDO($dsn, $_POST['db_user'], $_POST['db_pass']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->exec("CREATE DATABASE IF NOT EXISTS `{$_POST['db_name']}`");
    } catch (PDOException $e) {
        die("<h3>数据库连接失败：</h3><p>{$e->getMessage()}</p>");
    }

    // 3. 保存用户输入的配置到 .env
    $envConfig = [
        'APP_ENV=production',
        'APP_DEBUG=false',
        'DB_HOST=' . $_POST['db_host'],
        'DB_PORT=' . $_POST['db_port'],
        'DB_DATABASE=' . $_POST['db_name'],
        'DB_USERNAME=' . $_POST['db_user'],
        'DB_PASSWORD=' . $_POST['db_pass']
    ];
    file_put_contents('.env', implode("\n", $envConfig));

    // 4. 增强命令执行（添加错误处理）
    $commands = [
        'composer install --no-dev --optimize-autoloader',
        'php artisan key:generate --force',
        'php artisan migrate --force',
        'php artisan storage:link'
    ];
    
    foreach ($commands as $cmd) {
        $output = shell_exec("$cmd 2>&1");
        if (strpos($output, 'error') !== false) {
            die("<h3>安装失败：</h3><pre>$cmd\n$output</pre>");
        }
    }

    // 5. 清理安装脚本
    unlink(__FILE__);

    // 6. 创建管理员用户（新增代码块）
    try {
        $adminName = $_POST['admin_name'];
        $adminEmail = $_POST['admin_email'];
        $adminPassword = password_hash($_POST['admin_password'], PASSWORD_BCRYPT);

        $dsn = "mysql:host={$_POST['db_host']};port={$_POST['db_port']};dbname={$_POST['db_name']}";
        $db = new PDO($dsn, $_POST['db_user'], $_POST['db_pass']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $db->prepare("INSERT INTO users 
            (name, email, password, created_at, updated_at) 
            VALUES (?, ?, ?, NOW(), NOW())");
        $stmt->execute([$adminName, $adminEmail, $adminPassword]);
    } catch (PDOException $e) {
        die("<h3>管理员账号创建失败：</h3><pre>{$e->getMessage()}</pre>");
    }

    // 7. 安装成功提示（更新提示信息）
    die("<h3>✅ 安装成功！</h3>
        <div style='background:#e3f9eb; padding:1rem; border-radius:4px; margin:1rem 0'>
            <p>管理员账号信息：</p>
            <ul>
                <li>用户名：{$adminName}</li>
                <li>邮箱：{$adminEmail}</li>
            </ul>
        </div>
        <a href='/' style='display:inline-block; padding:0.5rem 1rem; background:#00b5ad; color:white; text-decoration:none; border-radius:4px'>访问首页</a>");
}
?>
<!-- 增强表单样式（新增CSS） -->
<style>
    :root {
        --primary-color: #00b5ad;
        --error-color: #ff4d4d;
        --success-color: #00c9a7;
    }
    
    body { 
        max-width: 800px; 
        margin: 2rem auto; 
        padding: 0 1rem; 
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        line-height: 1.6;
        color: #444;
    }
    
    form { 
        background: white; 
        padding: 2rem; 
        border-radius: 12px; 
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        border: 1px solid #eee;
    }
    
    h2 {
        color: var(--primary-color);
        margin: 0 0 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f0f0f0;
        font-size: 1.8rem;
    }
    
    label { 
        display: block; 
        margin: 1.5rem 0; 
        font-weight: 500;
    }
    
    input { 
        width: 100%; 
        padding: 0.75rem; 
        border: 1px solid #ddd; 
        border-radius: 6px; 
        margin-top: 0.5rem;
        transition: all 0.2s;
    }
    
    input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(0,181,173,0.1);
        outline: none;
    }
    
    input::placeholder {
        color: #999;
    }
    
    button { 
        background: var(--primary-color); 
        color: white; 
        padding: 1rem 2rem; 
        border: none; 
        border-radius: 6px; 
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s;
        width: 100%;
        margin-top: 1rem;
        font-size: 1.1rem;
    }
    
    button:hover {
        background: #009c94;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .required {
        color: var(--error-color);
        margin-left: 4px;
        font-size: 0.9em;
    }
    
    h3 {
        margin: 2rem 0 1rem;
        padding-top: 1.5rem;
        border-top: 1px solid #eee;
        color: #333;
        font-size: 1.2rem;
    }
</style>

<!-- 安装向导表单 -->
<form method="POST">
    <h2>📦 系统安装向导</h2>
    <label>数据库地址：
        <input type="text" name="db_host" value="localhost" required placeholder="通常为 localhost 或 127.0.0.1">
        <span class="required">*</span>
    </label>
    
    <label>端口：
        <input type="text" name="db_port" value="3306" required>
        <span class="required">*</span>
    </label>
    
    <label>数据库名：
        <input type="text" name="db_name" required placeholder="请先在数据库中创建该库">
        <span class="required">*</span>
    </label>
    
    <label>用户名：
        <input type="text" name="db_user" required>
        <span class="required">*</span>
    </label>
    
    <label>密码：
        <input type="password" name="db_pass" required>
        <span class="required">*</span>
    </label>
    
    <h3>👤 管理员设置</h3>
    <label>管理员用户名：
        <input type="text" name="admin_name" required placeholder="用于系统登录">
        <span class="required">*</span>
    </label>
    
    <label>管理员邮箱：
        <input type="email" name="admin_email" required placeholder="有效的邮箱地址">
        <span class="required">*</span>
    </label>
    
    <label>管理员密码：
        <input type="password" name="admin_password" required placeholder="至少8位字符">
        <span class="required">*</span>
    </label>
    
    <button type="submit">🚀 开始安装</button>
</form>