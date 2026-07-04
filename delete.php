<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$host = 'localhost';
$dbname = 'your_database_name';  // CHANGE THIS
$username_db = 'root';            // CHANGE THIS
$password_db = '';                // CHANGE THIS

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$username = $_SESSION['username'];

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['error'] = "No conversion ID provided.";
    header("Location: history.php");
    exit();
}

$id = $_GET['id'];

// Delete the conversion (only if it belongs to this user)
$sql = "DELETE FROM conversions WHERE id = :id AND username = :username";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id, ':username' => $username]);

if ($stmt->rowCount() > 0) {
    $_SESSION['success'] = "Conversion #$id deleted successfully!";
} else {
    $_SESSION['error'] = "Conversion not found or you don't have permission to delete it.";
}

header("Location: history.php");
exit();
?>