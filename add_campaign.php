<?php
include 'dbconnect.php';

$error = ''; // To store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $goal_amount = floatval($_POST['goal_amount']);
    $image = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "images/";
        $image_name = uniqid() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        // Validate file type and size
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($file_type, $allowed_types)) {
            $error = "Only JPG, PNG, and GIF files are allowed.";
        } elseif ($_FILES['image']['size'] > 5000000) { // 5MB limit
            $error = "File size must not exceed 5MB.";
        } else {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image = $image_name; // Store the image name in the database
            } else {
                $error = "Error uploading the image.";
            }
        }
    }

    if (!$error) {
        // Insert campaign into the database
        $sql = "INSERT INTO campaigns (name, description, goal_amount, image) VALUES (?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssds", $name, $description, $goal_amount, $image);
            if ($stmt->execute()) {
                header("location: admin_dashboard.php"); // Redirect to admin dashboard
                exit;
            } else {
                $error = "Database error: " . $conn->error;
            }
            $stmt->close();
        } else {
            $error = "Database error: " . $conn->error;
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Campaign</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Add Campaign</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
</body>
</html>
