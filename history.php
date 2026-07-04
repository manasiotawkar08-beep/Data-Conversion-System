<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Include database configuration
require_once 'config.php';
$pdo = getDBConnection();

$username = $_SESSION['username'];

// Handle Delete Request
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    
    // Verify the conversion belongs to this user before deleting
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
}

// Handle Delete All Request
if (isset($_GET['delete_all'])) {
    $sql = "DELETE FROM conversions WHERE username = :username";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':username' => $username]);
    
    $_SESSION['success'] = "All conversions deleted successfully!";
    header("Location: history.php");
    exit();
}

// Get all conversions for this user
$sql = "SELECT * FROM conversions WHERE username = :username ORDER BY created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':username' => $username]);
$conversions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Conversion History</title>
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
            background: #fff;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        h2 {
            font-size: 24px;
            color: #111827;
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            transition: 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        
        .btn-primary {
            background: #3b82f6;
            color: #fff;
        }
        
        .btn-primary:hover {
            background: #2563eb;
        }
        
        .btn-secondary {
            background: #6b7280;
            color: #fff;
        }
        
        .btn-secondary:hover {
            background: #4b5563;
        }
        
        .btn-danger {
            background: #ef4444;
            color: #fff;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
        
        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .alert-success {
            background: #d1fae5;
            color: #065f46;
        }
        
        .alert-error {
            background: #fecaca;
            color: #991b1b;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
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
        
        .no-data p {
            margin-top: 10px;
        }
        
        .delete-all-btn {
            margin-left: 10px;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
        }
        
        @media(max-width: 768px) {
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px;
            }
            .header {
                flex-direction: column;
                align-items: stretch;
            }
            .header-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>📊 Conversion History</h2>
            <div class="header-buttons">
                <a href="convert.php" class="btn btn-primary">➕ New Conversion</a>
                <a href="dashboard.php" class="btn btn-secondary">← Dashboard</a>
                <?php if (count($conversions) > 0): ?>
                    <a href="?delete_all=1" class="btn btn-danger delete-all-btn" 
                       onclick="return confirm('⚠️ Are you sure you want to delete ALL conversions? This cannot be undone!')">
                        🗑️ Delete All
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Display Messages -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?php 
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($conversions) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Input Name</th>
                        <th>Input Age</th>
                        <th>Temp (°C)</th>
                        <th>Date</th>
                        <th>Output</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($conversions as $conv): ?>
                        <tr>
                            <td>#<?php echo $conv['id']; ?></td>
                            <td><?php echo htmlspecialchars($conv['input_name']); ?></td>
                            <td><?php echo $conv['input_age']; ?> yrs</td>
                            <td><?php echo $conv['input_temperature']; ?>°C</td>
                            <td><?php echo date('d M Y', strtotime($conv['input_date'])); ?></td>
                            <td><?php echo htmlspecialchars($conv['output_name']); ?></td>
                            <td><span class="badge-success">✓ <?php echo $conv['status']; ?></span></td>
                            <td><?php echo date('d M Y, H:i', strtotime($conv['created_at'])); ?></td>
                            <td>
                                <div class="action-buttons">
                                    <a href="?delete=<?php echo $conv['id']; ?>" 
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete conversion #<?php echo $conv['id']; ?>?')">
                                        🗑️ Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="no-data">
                <p style="font-size: 18px; margin-bottom: 10px;">📭 No conversions yet</p>
                <p>Start by creating your first data conversion!</p>
                <a href="convert.php" class="btn btn-primary" style="margin-top: 15px;">Create First Conversion</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>