<?php

use app\core\Application;

class m0001_create_users_table
{

    public function up(): array
    {
        try {
            Application::$app->database->pdo->exec("
            
            CREATE TABLE users(
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(60) NOT NULL,
                email VARCHAR(255) NOT NULL,
                birthday DATE NOT NULL,
                password VARCHAR(60) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
            );
        ");
            return [TRUE];
        } catch (Exception $err) {
            return [FALSE, $err->getMessage()];
        }
    }

    public function down()
    {
        return "m0001_create_users_table finished rolling back";
    }

}
