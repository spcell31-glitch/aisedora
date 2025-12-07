<?php
echo "<h2>Проверка папки uploads</h2>";

$folders = [
    'uploads',
    'uploads/thumbs',
    '../uploads',
    '../../uploads'
];

foreach ($folders as $folder) {
    if (is_dir($folder)) {
        echo "✅ $folder - существует<br>";
        echo "&nbsp;&nbsp;Доступ на запись: " . (is_writable($folder) ? '✅ Да' : '❌ Нет') . "<br>";
    } else {
        echo "❌ $folder - НЕТ<br>";
    }
}

// Попробуем создать тестовый файл
$test_file = 'uploads/test.txt';
if (file_put_contents($test_file, 'test')) {
    echo "✅ Можем записать файл в uploads<br>";
    unlink($test_file);
} else {
    echo "❌ Не можем записать файл в uploads<br>";
}
?>