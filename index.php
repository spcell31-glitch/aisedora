<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    if (login($username, $password)) {
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Неверный логин или пароль';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в панель управления</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container mx-auto px-4">
        <div class="max-w-md mx-auto login-box p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Панель управления</h1>
                <p class="text-gray-600 mt-2">Войдите для управления сайтом</p>
            </div>
            
            <?php if ($error): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <?php echo csrf_field(); ?>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Логин</label>
                    <input type="text" name="username" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">Пароль</label>
                    <input type="password" name="password" required 
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                
                <button type="submit" 
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition">
                    Войти
                </button>
            </form>
            
            <div class="mt-6 text-center text-sm text-gray-600">
                <p>Данные для входа:</p>
                <p><strong>Логин:</strong> admin</p>
                <p><strong>Пароль:</strong> admin123</p>
                <p class="mt-2 text-red-500">Смените пароль после первого входа!</p>
            </div>
        </div>
    </div>
</body>
</html>