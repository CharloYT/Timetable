<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Timetable Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h1>Timetable Management</h1>
        <h2>Welcome Back</h2>
        
        <?php
        session_start();
        
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        
        $error = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'db_connect.php';
            
            $username = $_POST['username'];
            $password = $_POST['password'];
            
            $sql = "SELECT * FROM users WHERE username = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows == 1) {
                $user = $result->fetch_assoc();
                if (password_verify($password, $user['password_hash'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid username or password";
                }
            } else {
                $error = "Invalid username or password";
            }
        }
        ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required autofocus placeholder="Enter your username">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Enter your password">
            </div>
            
            <input type="submit" value="Login">
        </form>
        
        <p class="auth-link">Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</div>

</body>
</html>
