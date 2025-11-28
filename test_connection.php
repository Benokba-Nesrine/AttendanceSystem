<?php
// test_connection.php

require_once 'db_connect.php';

echo "<h2>Database Connection Test</h2>";

try {
    $pdo = getDatabaseConnection();
    echo "<span style='color: green; font-weight: bold;'>✓ Connection successful!</span><br>";
    echo "Connected to database: <strong>" . $pdo->query('SELECT DATABASE()')->fetchColumn() . "</strong>";
    
} catch (Exception $e) {
    echo "<span style='color: red; font-weight: bold;'>✗ Connection failed!</span><br>";
    echo "Error: " . htmlspecialchars($e->getMessage());
}