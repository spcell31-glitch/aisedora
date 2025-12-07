<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
check_auth();

// Получаем всех тренеров
$stmt = $pdo->query("SELECT * FROM trainers ORDER BY sort_order");
$trainers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управление тренерами</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font@6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h1 class="text-2xl font-bold mb-6">Управление тренерами</h1>
            
            <!-- Добавить нового тренера -->
            <div class="mb-8 p-4 border rounded bg-gray-50">
                <h2 class="text-lg font-bold mb-4">Добавить нового тренера</h2>
                <form id="addTrainerForm" class="space-y-4">
                    <?php echo csrf_field(); ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 mb-2">ФИО тренера:</label>
                            <input type="text" name="full_name" required class="w-full p-2 border rounded">
                        </div>
                        
                        <div>
                            <label class="block text-gray-700 mb-2">Должность:</label>
                            <input type="text" name="position" required class="w-full p-2 border rounded" 
                                   placeholder="Например: Тренер по художественной гимнастике">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-gray-700 mb-2">Факты о тренере (каждый с новой строки):</label>
                        <textarea name="facts" rows="5" class="w-full p-2 border rounded" 
                                  placeholder="Занимается гимнастикой с 3-х лет&#10;Кандидат в Мастера Спорта..."></textarea>
                        <p class="text-sm text-gray-500">Каждый факт с новой строки</p>
                    </div>
                    
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        Добавить тренера
                    </button>
                </form>
            </div>
            
            <!-- Список тренеров -->
            <div>
                <h2 class="text-lg font-bold mb-4">Список тренеров (<?php echo count($trainers); ?>)</h2>
                
                <div id="trainersList" class="space-y-4">
                    <?php foreach ($trainers as $trainer): ?>
                    <div class="trainer-item border rounded p-4 bg-white" data-id="<?php echo $trainer['id']; ?>">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg"><?php echo htmlspecialchars($trainer['full_name']); ?></h3>
                                <p class="text-gray-600 mt-1"><?php echo htmlspecialchars($trainer['position']); ?></p>
                                
                                <!-- Факты (свернуты по умолчанию) -->
                                <div class="mt-3">
                                    <button onclick="toggleFacts(<?php echo $trainer['id']; ?>)" 
                                            class="text-blue-600 text-sm">
                                        Показать факты (<?php echo count(explode("\n", $trainer['facts'])); ?>)
                                    </button>
                                    <div id="facts-<?php echo $trainer['id']; ?>" class="hidden mt-2 bg-gray-50 p-3 rounded">
                                        <pre class="whitespace-pre-line text-sm"><?php echo htmlspecialchars($trainer['facts']); ?></pre>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex space-x-2 ml-4">
                                <button onclick="editTrainer(<?php echo $trainer['id']; ?>)" 
                                        class="p-2 text-blue-600 hover:text-blue-800">
                                    <i class="mdi mdi-pencil"></i>
                                </button>
                                <button onclick="deleteTrainer(<?php echo $trainer['id']; ?>)" 
                                        class="p-2 text-red-600 hover:text-red-800">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Форма редактирования (скрыта) -->
                        <div id="editForm-<?php echo $trainer['id']; ?>" class="hidden mt-4 pt-4 border-t">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm mb-1">ФИО:</label>
                                    <input type="text" value="<?php echo htmlspecialchars($trainer['full_name']); ?>" 
                                           class="w-full p-2 border rounded name-input">
                                </div>
                                <div>
                                    <label class="block text-sm mb-1">Должность:</label>
                                    <input type="text" value="<?php echo htmlspecialchars($trainer['position']); ?>" 
                                           class="w-full p-2 border rounded position-input">
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <label class="block text-sm mb-1">Факты:</label>
                                <textarea class="w-full p-2 border rounded facts-input" rows="4"><?php echo htmlspecialchars($trainer['facts']); ?></textarea>
                            </div>
                            
                            <button onclick="saveTrainer(<?php echo $trainer['id']; ?>)" 
                                    class="mt-3 bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                Сохранить
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if (empty($trainers)): ?>
                    <p class="text-gray-500 text-center py-8">Тренеров пока нет. Добавьте первого!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    // Добавление нового тренера
    document.getElementById('addTrainerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('save-trainer.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            } else {
                alert('Ошибка: ' + result.message);
            }
        });
    });
    
    // Показать/скрыть факты
    function toggleFacts(id) {
        const element = document.getElementById('facts-' + id);
        element.classList.toggle('hidden');
    }
    
    // Редактирование тренера
    function editTrainer(id) {
        const editForm = document.getElementById('editForm-' + id);
        editForm.classList.toggle('hidden');
    }
    
    // Сохранение изменений
    function saveTrainer(id) {
        const item = document.querySelector(`[data-id="${id}"]`);
        const data = {
            id: id,
            full_name: item.querySelector('.name-input').value,
            position: item.querySelector('.position-input').value,
            facts: item.querySelector('.facts-input').value,
            csrf_token: document.querySelector('[name="csrf_token"]').value
        };
        
        fetch('save-trainer.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                location.reload();
            }
        });
    }
    
    // Удаление тренера
    function deleteTrainer(id) {
        if (confirm('Удалить этого тренера?')) {
            fetch('delete-trainer.php?id=' + id + '&csrf_token=' + document.querySelector('[name="csrf_token"]').value)
            .then(() => {
                document.querySelector(`[data-id="${id}"]`).remove();
            });
        }
    }
    </script>
</body>
</html>