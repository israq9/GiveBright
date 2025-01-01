<?php
session_start();
include 'dbconnect.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit;
}

// Handle donation submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaign_id'], $_POST['amount'])) {
    $campaign_id = intval($_POST['campaign_id']);
    $amount = floatval($_POST['amount']);

    if ($amount <= 0) {
        $message = "Invalid donation amount. Please enter a valid number.";
    } else {
        // Update the campaign's amount raised
        $sql = "UPDATE campaigns SET amount_raised = amount_raised + ? WHERE campaign_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("di", $amount, $campaign_id);
            if ($stmt->execute()) {
                $message = "Donation successful!";
            } else {
                $message = "Failed to process the donation. Please try again.";
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $conn->error;
        }
    }
}

// Fetch all campaigns
$sql = "SELECT * FROM campaigns";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav>
        <a href="home.php">Home</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>Available Campaigns</h1>

        <!-- Display donation success/error message -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <!-- Display campaigns -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="campaign">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <?php if (!empty($row['image'])): ?>
                        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Campaign Image" class="campaign-image">
                    <?php endif; ?>

                    <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
                    <p><strong>Goal Amount:</strong> $<?php echo number_format($row['goal_amount'], 2); ?></p>
                    <p><strong>Amount Donated:</strong> $<?php echo number_format($row['amount_raised'] ?? 0, 2); ?></p>

                    <!-- Progress bar -->
                    <?php
                    $amount_raised = $row['amount_raised'] ?? 0;
                    $goal_amount = $row['goal_amount'] ?? 1; // Avoid division by zero
                    $progress_percentage = min(100, ($amount_raised / $goal_amount) * 100);
                    ?>
                    <div class="progress-bar-container">
                        <div class="progress-bar" style="width: <?php echo $progress_percentage; ?>%;">
                            <?php echo round($progress_percentage); ?>%
                        </div>
                    </div>

                    <!-- Donation form -->
                    <form action="home.php" method="POST">
                        <input type="hidden" name="campaign_id" value="<?php echo $row['campaign_id']; ?>">
                        <input type="number" name="amount" placeholder="Enter donation amount" required>
                        <button type="submit">Donate</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No campaigns found.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2025 Donation Website</p>
    </footer>
</body>
</html>
