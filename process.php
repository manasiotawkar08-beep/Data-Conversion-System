<?php
session_start();

include("../includes/db.php");

$name = trim($_POST['name']);
$age = $_POST['age'];
$temp = $_POST['temperature'];
$date = $_POST['date'];

$status = "Success";

/* ---------- Conversion ---------- */

// Rename field
$output_name = $name;

// Same age
$output_age = $age;

// Convert temperature to one decimal place
$output_temperature = number_format($temp, 1);

// Convert date format
$output_date = date("Y-m-d", strtotime($date));

/* ---------- Save to Database ---------- */

$sql = "INSERT INTO conversions
(
input_name,
input_age,
input_temperature,
input_date,
output_name,
output_age,
output_temperature,
output_date,
status
)

VALUES
(
'$name',
'$age',
'$temp',
'$date',
'$output_name',
'$output_age',
'$output_temperature',
'$output_date',
'$status'
)";

mysqli_query($conn, $sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Conversion Result</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="login-box">

<h2>Conversion Successful</h2>

<p><strong>Full Name:</strong> <?php echo $output_name; ?></p>

<p><strong>User Age:</strong> <?php echo $output_age; ?></p>

<p><strong>Temperature:</strong> <?php echo $output_temperature; ?></p>

<p><strong>Date:</strong> <?php echo $output_date; ?></p>

<br>

<a href="../convert.php">
    <button>New Conversion</button>
</a>

<br><br>

<a href="../history.php">
    <button>View History</button>
</a>

</div>

</body>
</html>