<?php
include 'dbconnect.php';

$error = '';
if (isset($_GET['campaign_id'])) {
    $campaign_id = intval($_GET['campaign_id']);

    // Fetch the existing campaign details
    $sql = "SELECT * FROM campaigns WHERE campaign_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $campaign_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $campaign = $result->fetch_assoc();
        } else {
            $error = "Campaign not found.";
        }
        $stmt->close();
    } else {
        $error = "Error fetching campaign: " . $conn->error;
    }
} else {
    $error = "Invalid campaign ID.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $goal_amount = floatval($_POST['goal_amount']);

    // Update campaign details
    $sql = "UPDATE campaigns SET name = ?, description = ?, goal_amount = ? WHERE campaign_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssdi", $name, $description, $goal_amount, $campaign_id);
        if ($stmt->execute()) {
            header("location: admin_dashboard.php");
            exit;
        } else {
            $error = "Error updating campaign: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Campaign</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Campaign</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <?php if (isset($campaign)): ?>
            <form action="" method="post">
                <div class="form-group">
                    <label for="name">Campaign Name</label>
                    <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($campaign['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" required><?php echo htmlspecialchars($campaign['description']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="goal_amount">Goal Amount</label>
                    <input type="number" step="0.01" name="goal_amount" id="goal_amount" value="<?php echo htmlspecialchars($campaign['goal_amount']); ?>" required>
                </div>
                <button type="submit" class="btn">Update Campaign</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
