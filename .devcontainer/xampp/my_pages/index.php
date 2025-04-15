<?php

echo ("Hello, World!<br /><br />");
echo ("You're using PHP " . phpversion() . ".<br />");

// Test database
$serverName = "db";
$databaseName = "mydb";
$dbUsername = "root";
$dbPassword = "root";
$pdoOpts = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
$serverUri = "mysql:host={$serverName};dbname={$databaseName};charset=utf8mb4";

echo ("Connecting to: <a href=\"\">{$serverUri}</a><br />");

try {
    $pdo = new PDO($serverUri, $dbUsername, $dbPassword, $pdoOpts);
    echo ("Database connection successful.<br />");
} catch (Exception $ex) {
    echo ("Could not connect to the database: " . $ex->getMessage() . "<br />");
    error_log($ex->getMessage()); // ✅ fixed
    die();
}

try {
    $dbVersion = $pdo->query("SELECT VERSION()")->fetch()["VERSION()"];
    echo ("Detected database: {$dbVersion}<br />");
} catch (Exception $ex) {
    echo ("Could not get the database version info: " . $ex->getMessage() . "<br />");
    error_log($ex->getMessage()); // ✅ fixed
    die();
}
?>
