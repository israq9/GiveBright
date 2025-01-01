<?php
session_start();
include 'dbconnect.php';

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit;
}

// Fetch campaigns ending in the next 10 days
$sql = "SELECT * FROM campaigns WHERE end_date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 10 DAY)";
$result = $conn->query($sql);

if (!$result) {
    die("Database error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaigns Ending Soon</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
            color: white;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
        }

        nav a:last-child {
            margin-right: 0;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .campaign {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            display: flex;
            background-color: #f4f4f4;
        }

        .campaign img {
            max-width: 200px;
            margin-right: 20px;
            border-radius: 10px;
        }

        .campaign-content {
            flex: 1;
        }

        .campaign-content h3 {
            margin: 0;
            font-size: 1.5em;
            color: #007bff;
        }

        .campaign-content h3:hover {
            text-decoration: underline;
        }

        .campaign-content p {
            margin: 5px 0;
        }

        footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
        }
    </style>
</head>
<body>
    <nav>
        <a href="home.php">Home</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>Campaigns Ending Soon</h1>
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="campaign">
                    <img src="<?php echo htmlspecialchars($row['image'] ?? 'default.jpg'); ?>" alt="Campaign Image">
                    <div class="campaign-content">
                        <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
                        <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
                        <p><strong>Goal Amount:</strong> $<?php echo number_format($row['goal_amount'], 2); ?></p>
                        <p><strong>Amount Donated:</strong> $<?php echo number_format($row['raised_amount'], 2); ?></p>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No campaigns ending soon.</p>
        <?php endif; ?>
    </div>
    <footer>
        <p>&copy; 2025 Donation Website</p>
    </footer>
</body>
</html>
