<?php
// Function to load environment variables from .env file
function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[$name] = trim($value);
    }
    return true;
}

// Path to your .env file
$envFilePath = __DIR__ . '/../.env';

// Load environment variables
loadEnv($envFilePath);

// Access environment variables
$hostName = $_ENV['HOST_NAME'];
$dbName = $_ENV['MYSQL_DATABASE'];
$dbUser = $_ENV['MYSQL_USER'];
$dbPassword = $_ENV['MYSQL_PASSWORD'];

// Example connection using PDO
try {
    $pdo = new PDO("mysql:host=$hostName;dbname=$dbName", $dbUser, $dbPassword);
    echo "Connected successfully to the database '$dbName' at host '$hostName'";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>