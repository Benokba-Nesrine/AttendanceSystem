<?php
// db_connect.php
function getDatabaseConnection(): PDO
{
    $config = require 'config.php';
    $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

    try {
        return new PDO($dsn, $config['username'], $config['password'], $config['options']);
    } catch (PDOException $e) {
        // In production you would log this, not show it
        die("Database connection failed: " . $e->getMessage());
    }
}