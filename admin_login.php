<?php
include 'dbconnect.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the form fields are set
    if (isset($_POST['userid']) && isset($_POST['password'])) {
        // Capture and sanitize user input
        $userid = trim($_POST['userid']);
        $password = trim($_POST['password']);

        // Query the admins table
        $sql = "SELECT admin_id, password FROM admins WHERE userid = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $userid);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($admin_id, $stored_password);
                if ($stmt->fetch()) {
                    // Verify the password without hashing
                    if ($password === $stored_password) {
                        // Set session variables for admin
                        $_SESSION['admin_loggedin'] = true;
                        $_SESSION['admin_id'] = $admin_id;
                        $_SESSION['admin_userid'] = $userid;

                        // Redirect to admin dashboard
                        header("location: admin_dashboard.php");
                        exit;
                    } else {
                        $error = "Invalid password.";
                    }
                }
            } else {
                $error = "No admin account found with that userid.";
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    } else {
        $error = "Please fill in all fields.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="styles.css">
	<style>
    /* General Styling */
    body {
        font-family: Arial, sans-serif;
        line-height: 1.6;
        margin: 0;
        padding: 0;
        background-color: #f4f4f9;
        color: #333;
        display: flex;
        flex-direction: column;
        min-height: 100vh;

        background-image: url('uploads/3402393.jpg'); /* Replace with the path to your image */
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .container {
        width: 400px;
        margin: auto;
        text-align: center;
        flex: 1;
        background: rgba(255, 255, 255, 0.15); /* Slightly transparent white */
        border: 2px solid rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        color: #fff;
        border-radius: 15px; /* Rounded corners */
        padding: 40px 30px;
    }
	.co {
            width: 400px; 
            height: 500px;
            margin: auto;
            text-align: center;
            flex: 1;
			justify-content: center;
            align-items: center;
        }

    .ii {
        display: block;
        margin-bottom: 10px; /* Spacing between labels and inputs */
        font-weight: bold;
        color: #fff; /* White labels for better contrast */
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: none;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.8);
        color: #333;
        font-size: 1rem;
    }

    button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007bff;
        color: #fff;
        font-size: 1rem;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .error {
        color: #ff4d4f; /* Red color for error messages */
        font-weight: bold;
        margin-top: 20px;
    }
</style>


    </style>
</head>
<body>
    <div class="co"></div>
    <div class="container">
        <h2>Admin Login</h2>
        <form action="" method="post">
            <label class='ii'for="userid">User ID</label>
            <input type="text" name="userid" id="userid" required>
            <label class='ii' for="password">Password</label>
            <input type="password" name="password" id="password" required>
            <button type="submit">Login</button>
        </form>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    </div>
    <div class="co"></div>
</body>
</html>
