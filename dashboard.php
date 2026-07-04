<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';
$pdo = getDBConnection();

$username = $_SESSION['username'];

// Get total conversions count
$sql = "SELECT COUNT(*) as total FROM conversions";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$total_conversions = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

// Get successful conversions
$sql = "SELECT COUNT(*) as successful FROM conversions WHERE status = 'Success'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$successful = $stmt->fetch(PDO::FETCH_ASSOC)['successful'];

// Get failed conversions
$sql = "SELECT COUNT(*) as failed FROM conversions WHERE status = 'Failed'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$failed = $stmt->fetch(PDO::FETCH_ASSOC)['failed'];

// Get recent 5 conversions
$sql = "SELECT * FROM conversions ORDER BY id DESC LIMIT 5";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$recent_conversions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h1 {
            font-size: 28px;
            color: #111827;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-info span {
            color: #6b7280;
        }
        
        .btn-logout {
            padding: 8px 16px;
            background: #ef4444;
            color: white;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }
        
        .btn-logout:hover {
            background: #dc2626;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card .label {
            font-size: 13px;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .stat-card .number {
            font-size: 32px;
            font-weight: 700;
            color: #111827;
            margin-top: 5px;
        }
        
        .stat-card .number.green {
            color: #10b981;
        }
        
        .stat-card .number.red {
            color: #ef4444;
        }
        
        .welcome {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .welcome h2 {
            font-size: 22px;
            color: #111827;
        }
        
        .welcome p {
            color: #6b7280;
            margin-top: 5px;
        }
        
        .btn-primary {
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary:hover {
            background: #2563eb;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }
        
        .recent-section {
            background: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .recent-section h3 {
            font-size: 18px;
            color: #111827;
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f9fafb;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            color: #6b7280;
            font-weight: 600;
            border-bottom: 2px solid #e5e7eb;
        }
        
        td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        
        tr:hover {
            background: #f9fafb;
        }
        
        .badge-success {
            background: #d1fae5;
            color: #065f46;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }
        
        @media(max-width: 768px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .welcome {
                flex-direction: column;
                text-align: center;
            }
            
            .welcome .btn-primary {
                margin-top: 15px;
            }
            
            table {
                font-size: 12px;
            }
            
            th, td {
                padding: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📊 Dashboard</h1>
            <div class="user-info">
                <span>👤 <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="btn-logout">Logout</a>
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-card">
                <div class="label">Total Conversions</div>
                <div class="number"><?php echo $total_conversions; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Successful</div>
                <div class="number green"><?php echo $successful; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Failed</div>
                <div class="number red"><?php echo $failed; ?></div>
            </div>
            <div class="stat-card">
                <div class="label">Logged User</div>
                <div class="number" style="font-size: 18px;"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
            </div>
        </div>
        
        <div class="welcome">
            <div>
                <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 🚀</h2>
                <p>Your data conversion system is running smoothly with real-time analytics.</p>
            </div>
            <a href="convert.php" class="btn-primary">➕ New Conversion</a>
        </div>
        
        <div class="recent-section">
            <h3>📋 Recent Conversions</h3>
            
            <?php if (count($recent_conversions) > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Temperature</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_conversions as $conv): ?>
                            <tr>
                                <td>#<?php echo $conv['id']; ?></td>
                                <td><?php echo htmlspecialchars($conv['input_name']); ?></td>
                                <td><?php echo $conv['input_age']; ?></td>
                                <td><?php echo $conv['input_temperature']; ?>°C</td>
                                <td><?php echo date('d M Y', strtotime($conv['input_date'])); ?></td>
                                <td><span class="badge-success">✓ <?php echo $conv['status']; ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p style="font-size: 18px;">📭 No conversions yet</p>
                    <p>Start by creating your first data conversion!</p>
                    <a href="convert.php" class="btn-primary" style="margin-top: 15px;">Create First Conversion</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>