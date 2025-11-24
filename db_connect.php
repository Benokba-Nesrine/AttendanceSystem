<?php
require_once "config.php";

function getConnection() {
    global $DB_HOST, $DB_USER, $DB_PASS, $DB_NAME;

    try {
        $dsn = "mysql:host=$DB_HOST;dbname=$DB_NAME;charset=utf8";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

        $conn = new PDO($dsn, $DB_USER, $DB_PASS, $options);
        return $conn;

    } catch (PDOException $e) {
        error_log(date("Y-m-d H:i:s") . " - DB Error: " . $e->getMessage() . "\n", 3, "db_errors.log");
        return false;
    }
}