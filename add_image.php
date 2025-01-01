<?php
include 'dbconnect.php';

$error = '';
if (isset($_GET['campaign_id'])) {
    $campaign_id = intval($_GET['campaign_id']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                    $sql = "UPDATE campaigns SET image = ? WHERE campaign_id = ?";
                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("si", $image_name, $campaign_id);
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
                } else {
                    $error = "Error uploading the image.";
                }
            }
        } else {
            $error = "Please select an image to upload.";
        }
    }
} else {
    $error = "Invalid campaign ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Image</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Add Image to Campaign</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="image">Upload Image</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn">Add Image</button>
        </form>
    </div>
</body>
</html>
