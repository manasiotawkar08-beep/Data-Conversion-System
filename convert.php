<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>New Conversion</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter', sans-serif;
}

body{
    background:#f4f6fb;
}

/* CENTER CONTAINER */
.container{
    max-width:800px;
    margin:60px auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* TITLE */
h2{
    margin-bottom:20px;
    font-size:22px;
}

/* FORM GRID */
.form-group{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

label{
    font-size:13px;
    color:#6b7280;
    margin-bottom:5px;
    display:block;
}

input{
    width:100%;
    padding:12px;
    border:1px solid #e5e7eb;
    border-radius:10px;
    outline:none;
    transition:0.3s;
    font-size:14px;
}

input:focus{
    border-color:#3b82f6;
    box-shadow:0 0 0 3px rgba(59,130,246,0.1);
}

/* BUTTON */
.btn{
    margin-top:20px;
    width:100%;
    padding:12px;
    background:#3b82f6;
    color:#fff;
    border:none;
    border-radius:10px;
    font-size:15px;
    cursor:pointer;
    transition:0.3s;
}

.btn:hover{
    background:#2563eb;
}

/* BACK LINK */
.back{
    display:inline-block;
    margin-top:15px;
    text-decoration:none;
    color:#6b7280;
    font-size:14px;
}

.back:hover{
    color:#111827;
}

/* RESPONSIVE */
@media(max-width:600px){
    .form-group{
        grid-template-columns:1fr;
    }
}
</style>

</head>

<body>

<div class="container">

    <h2>➕ New Data Conversion</h2>

    <form action="convert_process.php" method="POST">

        <div class="form-group">

            <div>
                <label>Name</label>
                <input type="text" name="name" placeholder="Enter name" required>
            </div>

            <div>
                <label>Age</label>
                <input type="number" name="age" placeholder="Enter age" required>
            </div>

            <div>
                <label>Temperature</label>
                <input type="text" name="temperature" placeholder="Enter temperature" required>
            </div>

            <div>
                <label>Date</label>
                <input type="date" name="date" required>
            </div>

        </div>

        <button class="btn" type="submit">Convert Data</button>

    </form>

    <a class="back" href="dashboard.php">← Back to Dashboard</a>

</div>

</body>
</html>