<?php
session_start();

include("db.php"); //  database connection code using mysqli_connect


if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $gmail = $_POST['mail'];
    $password = $_POST['pass'];

    if (!empty($gmail) && !empty($password) && !is_numeric($gmail)) {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM 4431644_register.form WHERE email = ? LIMIT 1";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "s", $gmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);

            if ($user_data['pass'] == $password) {
                $_SESSION['user_id'] = $user_data['id']; // Assuming 'id' is the primary key of the user table
                $_SESSION['login_time'] = time(); // Set login time

                // Set cookie to last for 24 hours
                setcookie("user_id", $user_data['id'], time() + (24 * 3600)); // 24 hours in seconds
                setcookie("login_time", time(), time() + (24 * 3600)); // 24 hours in seconds

                header("Location: index.html"); // Corrected header redirect
                exit(); // Terminating script execution after redirect
            }
        }

        echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
    } else {
        echo "<script type='text/javascript'> alert('Wrong username or password')</script>";
    }
}

// Check if the session is set and if the login time is more than 10 seconds ago
if (isset($_SESSION['user_id']) && isset($_SESSION['login_time']) && (time() - $_SESSION['login_time'] > 10)) {
    // Unset all of the session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();
}

// Check if the cookie is set and if the login time is more than 24 hours ago
if (isset($_COOKIE['user_id']) && isset($_COOKIE['login_time']) && (time() - $_COOKIE['login_time'] > (24 * 3600))) {
    // Expire the cookies
    setcookie("user_id", "", time() - 3600);
    setcookie("login_time", "", time() - 3600);
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
    <div class="login">
        <h1>Login</h1>
        
        <!-- Specify the action attribute to submit data to the same PHP file -->
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            
            <label>Email</label>
            <input type="email" name="mail" required>
            <label>Password</label>
            <input type="password" name="pass" required>
            <input type="submit" name="submit">
        </form>

        <p>Not have an account? <a href="signup.php">Sign Up here</a></p>
    
    </div>
</body>
</html>
