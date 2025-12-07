<?php
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            // ДЛЯ OPENSERVER ИСПОЛЬЗУЙТЕ ЭТИ НАСТРОЙКИ:
            $this->pdo = new PDO(
                'mysql:host=localhost;dbname=cms_site;charset=utf8mb4',
                'root',      // имя пользователя OpenServer
                ''       // пароль OpenServer
            );
            
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            // Для отладки показываем ошибку
            die("Ошибка подключения к базе данных: " . $e->getMessage());
        }
    }
    
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
}

// Создаем глобальный объект БД
$pdo = Database::getInstance()->getConnection();
?>