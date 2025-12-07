<?php
require_once 'includes/config.php';

echo "<h2>Тест загрузки файла</h2>";

if ($_FILES) {
    echo "<pre>";
    print_r($_FILES);
    echo "</pre>";
    
    $tmp_name = $_FILES['file']['tmp_name'];
    $name = $_FILES['file']['name'];
    
    if (move_uploaded_file($tmp_name, 'uploads/' . $name)) {
        echo "✅ Файл загружен!<br>";
        echo "<img src='uploads/$name' width='200'>";
    } else {
        echo "❌ Ошибка загрузки<br>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file">
    <button>Загрузить тест</button>
</form>