<?php
include 'dbconnect.php';
session_start();

// Check if campaign_id is provided in the URL
if (!isset($_GET['campaign_id']) || empty($_GET['campaign_id'])) {
    die("Invalid campaign.");
}

$campaign_id = intval($_GET['campaign_id']);

// Fetch campaign details from the database
$sql = "SELECT * FROM campaigns WHERE campaign_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $campaign_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Campaign not found.");
}

$campaign = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($campaign['name']); ?> - Campaign Details</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($campaign['name']); ?></h1>
        <?php if (!empty($campaign['image'])): ?>
            <img src="images/<?php echo htmlspecialchars($campaign['image']); ?>" alt="Campaign Image" style="width:300px; height:300px; object-fit:cover;">
        <?php endif; ?>
        <p><strong>Description:</strong> <?php echo htmlspecialchars($campaign['description']); ?></p>
        <p><strong>Goal Amount:</strong> $<?php echo number_format($campaign['goal_amount'], 2); ?></p>
        <p><strong>Raised Amount:</strong> $<?php echo number_format($campaign['raised_amount'], 2); ?></p>
        
        <h2>Make a Donation</h2>
        <form action="donate.php" method="post">
            <input type="hidden" name="campaign_id" value="<?php echo $campaign['campaign_id']; ?>">
            <label for="amount">Enter Amount:</label>
            <input type="number" id="amount" name="amount" placeholder="Enter amount" min="1" required>
            <button type="submit" class="btn">Donate</button>
        </form>

        <a href="home.php" class="btn">Back to Campaigns</a>
    </div>
</body>
</html>
