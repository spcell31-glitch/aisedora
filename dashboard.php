<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
check_auth();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель управления</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Боковая панель -->
        <div class="w-64 bg-gray-800 text-white min-h-screen">
            <div class="p-4">
                <div class="flex items-center space-x-3 mb-8">
                    <div class="bg-blue-500 p-2 rounded">
                        <i class="mdi mdi-cog text-xl"></i>
                    </div>
                    <span class="text-xl font-bold">Управление</span>
                </div>
                
                <nav class="space-y-2">
                    <a href="dashboard.php" class="block p-3 rounded hover:bg-gray-700 bg-gray-700">
                        <i class="mdi mdi-view-dashboard mr-2"></i> Обзор
                    </a>
                    <a href="pages/edit-home.php" class="block p-3 rounded hover:bg-gray-700">
                        <i class="mdi mdi-home mr-2"></i> Главная
                    </a>
                    <a href="pages/edit-trainers.php" class="block p-3 rounded hover:bg-gray-700">
                        <i class="mdi mdi-account-group mr-2"></i> Тренеры
                    </a>
                    <a href="pages/edit-services.php" class="block p-3 rounded hover:bg-gray-700">
                        <i class="mdi mdi-briefcase mr-2"></i> Услуги
                    </a>
                    <a href="pages/edit-gallery.php" class="block p-3 rounded hover:bg-gray-700">
                        <i class="mdi mdi-image-multiple mr-2"></i> Галерея
                    </a>
                    <a href="pages/edit-contacts.php" class="block p-3 rounded hover:bg-gray-700">
                        <i class="mdi mdi-contacts mr-2"></i> Контакты
                    </a>
                </nav>
                
                <div class="mt-8 pt-8 border-t border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                            <?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?>
                        </div>
                        <div>
                            <p class="font-medium"><?php echo htmlspecialchars($_SESSION['username']); ?></p>
                            <p class="text-sm text-gray-400">Администратор</p>
                        </div>
                    </div>
                    
                    <a href="logout.php" class="block mt-4 p-3 rounded hover:bg-red-900 text-red-300">
                        <i class="mdi mdi-logout mr-2"></i> Выход
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Основной контент -->
        <div class="flex-1">
            <!-- Верхняя панель -->
            <header class="bg-white shadow-sm border-b px-6 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Добро пожаловать, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                        <p class="text-gray-600">Панель управления сайтом</p>
                    </div>
                </div>
            </header>
            
            <!-- Контент -->
            <main class="p-6">
                <div class="mb-8">
                    <h2 class="text-xl font-bold mb-4">Быстрые действия</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="pages/edit-home.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                            <div class="flex items-center space-x-4">
                                <div class="bg-blue-100 p-3 rounded">
                                    <i class="mdi mdi-text-box-edit text-2xl text-blue-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold">Редактировать текст</h3>
                                    <p class="text-gray-600 text-sm">Изменить текст на главной</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="pages/edit-gallery.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                            <div class="flex items-center space-x-4">
                                <div class="bg-green-100 p-3 rounded">
                                    <i class="mdi mdi-image-plus text-2xl text-green-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold">Добавить фото</h3>
                                    <p class="text-gray-600 text-sm">В галерею или слайдер</p>
                                </div>
                            </div>
                        </a>
                        
                        <a href="pages/edit-services.php" class="bg-white rounded-lg shadow p-6 hover:shadow-md transition">
                            <div class="flex items-center space-x-4">
                                <div class="bg-yellow-100 p-3 rounded">
                                    <i class="mdi mdi-briefcase-plus text-2xl text-yellow-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-bold">Управление услугами</h3>
                                    <p class="text-gray-600 text-sm">Добавить или изменить услуги</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Инструкция -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-bold text-blue-800 mb-3">🎯 Как пользоваться панелью управления:</h3>
                    <ul class="space-y-2 text-blue-700">
                        <li>1. Для изменения текста - перейдите в раздел "Главная"</li>
                        <li>2. Для добавления фото - перейдите в "Галерея"</li>
                        <li>3. Все изменения сохраняются автоматически</li>
                        <li>4. Не бойтесь что-то сломать - всегда можно отменить</li>
                    </ul>
                </div>
            </main>
        </div>
    </div>
</body>
</html>