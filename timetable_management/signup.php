<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Timetable Management System</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-container">
    <div class="auth-card">
        <h1>Timetable Management</h1>
        <h2>Create Account</h2>
        
        <?php
        session_start();
        
        // Redirect if already logged in
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php");
            exit();
        }
        
        $error = "";
        $success = "";
        
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'db_connect.php';
            
            $username = $_POST['username'];
            $email = $_POST['email'];
            $full_name = $_POST['full_name'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];
            
            // Validation
            if ($password !== $confirm_password) {
                $error = "Passwords do not match!";
            } elseif (strlen($password) < 6) {
                $error = "Password must be at least 6 characters long!";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                
                $sql = "INSERT INTO users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $username, $email, $password_hash, $full_name);
                
                if ($stmt->execute()) {
                    $success = "Account created successfully! You can now <a href='login.php'>login</a>.";
                } else {
                    if ($conn->errno == 1062) {
                        $error = "Username or email already exists!";
                    } else {
                        $error = "Error: " . $conn->error;
                    }
                }
            }
        }
        ?>
        
        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php else: ?>
        
        <form method="post" action="">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required placeholder="Choose a username">
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required placeholder="Create a password">
            </div>
            
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" required placeholder="Confirm your password">
            </div>
            
            <input type="submit" value="Sign Up">
        </form>
        
        <?php endif; ?>
        
        <p class="auth-link">Already have an account? <a href="login.php">Login</a></p>
    </div>
</div>

</body>
</html>
