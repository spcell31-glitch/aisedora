<?php
// Очистка входных данных
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Безопасный SQL запрос
function safe_query($sql, $params = []) {
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

// Создание миниатюр
function create_thumbnail($src, $dest, $width = 200, $height = 200) {
    if (!file_exists($src)) return false;
    
    list($src_width, $src_height, $type) = getimagesize($src);
    
    switch($type) {
        case IMAGETYPE_JPEG: $source = imagecreatefromjpeg($src); break;
        case IMAGETYPE_PNG: $source = imagecreatefrompng($src); break;
        case IMAGETYPE_GIF: $source = imagecreatefromgif($src); break;
        default: return false;
    }
    
    $ratio = min($width/$src_width, $height/$src_height);
    $new_width = $src_width * $ratio;
    $new_height = $src_height * $ratio;
    
    $thumb = imagecreatetruecolor($new_width, $new_height);
    
    // Сохраняем прозрачность для PNG и GIF
    if($type == IMAGETYPE_PNG || $type == IMAGETYPE_GIF) {
        imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }
    
    imagecopyresampled($thumb, $source, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);
    
    switch($type) {
        case IMAGETYPE_JPEG: imagejpeg($thumb, $dest, 85); break;
        case IMAGETYPE_PNG: imagepng($thumb, $dest, 9); break;
        case IMAGETYPE_GIF: imagegif($thumb, $dest); break;
    }
    
    imagedestroy($source);
    imagedestroy($thumb);
    
    return true;
}
?>