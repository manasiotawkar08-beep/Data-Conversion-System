<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include database configuration
require_once 'config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    $pdo = getDBConnection();
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Get form data
    $name = $_POST['name'] ?? '';
    $age = $_POST['age'] ?? '';
    $temperature = $_POST['temperature'] ?? '';
    $date = $_POST['date'] ?? '';
    
    // Validate inputs
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($age) || !is_numeric($age) || $age < 0) {
        $errors[] = "Valid age is required";
    }
    
    if (empty($temperature) || !is_numeric($temperature)) {
        $errors[] = "Valid temperature is required";
    }
    
    if (empty($date)) {
        $errors[] = "Date is required";
    }
    
    // If there are errors, redirect back with error message
    if (!empty($errors)) {
        $_SESSION['error'] = implode(", ", $errors);
        header("Location: convert.php");
        exit();
    }
    
    // PERFORM CONVERSIONS
    $celsius = floatval($temperature);
    $fahrenheit = ($celsius * 9/5) + 32;
    $kelvin = $celsius + 273.15;
    $age_in_months = $age * 12;
    $age_in_days = $age * 365;
    $formatted_date = date("F j, Y", strtotime($date));
    $day_of_week = date("l", strtotime($date));
    
    // SAVE TO DATABASE
    try {
        $username = $_SESSION['username'];
        
        $sql = "INSERT INTO conversions (
                    input_name,
                    input_age,
                    input_temperature,
                    input_date,
                    output_name,
                    output_age,
                    output_temperature,
                    output_date,
                    status
                ) VALUES (
                    :input_name,
                    :input_age,
                    :input_temperature,
                    :input_date,
                    :output_name,
                    :output_age,
                    :output_temperature,
                    :output_date,
                    :status
                )";
        
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute([
            ':input_name' => $name,                                    
            ':input_age' => $age,                                      
            ':input_temperature' => $celsius,                          
            ':input_date' => $date,                                    
            ':output_name' => strtoupper($name),                       
            ':output_age' => $age_in_months,  // Store as number (216) 
            ':output_temperature' => number_format($fahrenheit, 2) . ' °F',
            ':output_date' => $formatted_date,                        
            ':status' => 'Success'
        ]);
        
        if ($result) {
            $conversion_id = $pdo->lastInsertId();
            
            $_SESSION['conversion_result'] = [
                'id' => $conversion_id,
                'name' => htmlspecialchars($name),
                'age' => $age,
                'age_months' => $age_in_months,
                'age_days' => $age_in_days,
                'temperature_celsius' => $celsius,
                'temperature_fahrenheit' => number_format($fahrenheit, 2),
                'temperature_kelvin' => number_format($kelvin, 2),
                'date' => $date,
                'formatted_date' => $formatted_date,
                'day_of_week' => $day_of_week,
                'conversion_time' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['success'] = "✅ Data saved successfully! ID: #$conversion_id";
            header("Location: result.php");
            exit();
        } else {
            $_SESSION['error'] = "Failed to save data";
            header("Location: convert.php");
            exit();
        }
        
    } catch(PDOException $e) {
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        error_log("PDO Error: " . $e->getMessage());
        header("Location: convert.php");
        exit();
    }
    
} else {
    header("Location: convert.php");
    exit();
}
?>