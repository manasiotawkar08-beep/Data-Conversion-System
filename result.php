<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Check if conversion result exists
if (!isset($_SESSION['conversion_result'])) {
    header("Location: convert.php");
    exit();
}

$data = $_SESSION['conversion_result'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Conversion Results</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background: #f4f6fb;
        }
        
        .container {
            max-width: 700px;
            margin: 60px auto;
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        h2 {
            margin-bottom: 20px;
            font-size: 22px;
            color: #111827;
        }
        
        .result-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .result-item {
            background: #f9fafb;
            padding: 15px;
            border-radius: 10px;
            border-left: 4px solid #3b82f6;
        }
        
        .result-item .label {
            font-size: 12px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }
        
        .result-item .value {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-top: 5px;
        }
        
        .btn {
            margin-top: 20px;
            padding: 12px 24px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #6b7280;
            margin-left: 10px;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .success-badge {
            background: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            display: inline-block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        @media(max-width:600px) {
            .result-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>📊 Conversion Results</h2>
        <div class="success-badge">✅ Data Converted Successfully</div>
        
        <div class="result-grid">
            <div class="result-item">
                <div class="label">Name</div>
                <div class="value"><?php echo $data['name']; ?></div>
            </div>
            
            <div class="result-item">
                <div class="label">Age</div>
                <div class="value"><?php echo $data['age']; ?> years</div>
            </div>
            
            <div class="result-item">
                <div class="label">Age in Months</div>
                <div class="value"><?php echo $data['age_months']; ?> months</div>
            </div>
            
            <div class="result-item">
                <div class="label">Age in Days</div>
                <div class="value"><?php echo number_format($data['age_days']); ?> days</div>
            </div>
            
            <div class="result-item">
                <div class="label">Temperature (Celsius)</div>
                <div class="value"><?php echo $data['temperature_celsius']; ?> °C</div>
            </div>
            
            <div class="result-item">
                <div class="label">Temperature (Fahrenheit)</div>
                <div class="value"><?php echo $data['temperature_fahrenheit']; ?> °F</div>
            </div>
            
            <div class="result-item">
                <div class="label">Temperature (Kelvin)</div>
                <div class="value"><?php echo $data['temperature_kelvin']; ?> K</div>
            </div>
            
            <div class="result-item">
                <div class="label">Date</div>
                <div class="value"><?php echo $data['formatted_date']; ?></div>
            </div>
            
            <div class="result-item">
                <div class="label">Day of Week</div>
                <div class="value"><?php echo $data['day_of_week']; ?></div>
            </div>
            
            <div class="result-item">
                <div class="label">Conversion Time</div>
                <div class="value"><?php echo $data['conversion_time']; ?></div>
            </div>
        </div>
        
        <a href="convert.php" class="btn">➕ New Conversion</a>
        <a href="dashboard.php" class="btn btn-secondary">← Back to Dashboard</a>
    </div>
</body>
</html>