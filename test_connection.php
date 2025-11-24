<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "db_connect.php";

$conn = getConnection();

if ($conn) {
    echo "Connection successful";
} else {
    echo "Connection failed";
}