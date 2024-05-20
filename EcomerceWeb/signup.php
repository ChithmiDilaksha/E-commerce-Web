<?php
session_start();

include("db.php"); // Assuming this file contains the correct database connection code using mysqli_connect

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $firstname = $_POST['fname'];
    $lastname = $_POST['lname'];
    $gender = $_POST['gender'];
    $num = $_POST['number'];
    $address = $_POST['add'];
    $email = $_POST['mail'];
    $password = $_POST['pass'];

    // Basic form validation
    if (!empty($email) && !empty($password) && !is_numeric($email)) 
    {
        // SQL injection prevention: Use prepared statements
        $query = "INSERT INTO 4431644_register.form (fname, lname, gender, cnum, address, email, pass) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "sssssss", $firstname, $lastname, $gender, $num, $address, $email, $password);
        mysqli_stmt_execute($stmt);

        // Check if the query was successful
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "<script type='text/javascript'> alert('Successfully Registered')</script>";
        } else {
            echo "<script type='text/javascript'> alert('Registration Failed')</script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script type='text/javascript'> alert('Please Enter Valid Information')</script>";
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form Login and Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="signup">
        <h1>Sign up</h1>
        <h4>It's Free and only take a minute</h4>
        <form method="POST">
            <label>First Name</label>
            <input type="text" name="fname" required>
            <label>Last Name</label>
            <input type="text" name="lname" required>
            <label>Gender</label>
            <input type="text" name="gender" required>
            <label>Contact Address</label>
            <input type="tel" name="number" required>
            <label>Address</label>
            <input type="text" name="add" required>
            <label>Email</label>
            <input type="email" name="mail" required>
            <label>Password</label>
            <input type="password" name="pass" required>
            <input type="submit" name="submit">
        </form>
        <p>By Clicking the Sign Up button, you agree to our<br>
            <a href="">Terms and Conditions</a>and<a href="#">Policy Privacy</a>
        </p>
        <p>Already have an Account? <a href="login.php">Login Here</a></p>
        
    </div>

</body>
</html>
