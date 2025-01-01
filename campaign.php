<?php
session_start();
include 'dbconnect.php';

// Enable debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Check if the campaign ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid campaign ID.");
}

$campaign_id = intval($_GET['id']);

// Fetch campaign details from the database
$sql = "SELECT * FROM campaigns WHERE campaign_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("SQL Error: " . $conn->error);
}

$stmt->bind_param("i", $campaign_id);
if (!$stmt->execute()) {
    die("Execution Error: " . $stmt->error);
}

$result = $stmt->get_result();
if ($result === false) {
    die("Result Error: " . $conn->error);
}

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
    <title><?php echo htmlspecialchars($campaign['name']); ?></title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        img {
            max-width: 100%;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .campaign-details p {
            margin: 10px 0;
        }

        .donate-form {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f4f4f4;
        }

        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($campaign['name']); ?></h1>
        <?php if (!empty($campaign['image'])): ?>
            <img src="<?php echo htmlspecialchars($campaign['image']); ?>" alt="Campaign Image">
        <?php endif; ?>

        <div class="campaign-details">
            <p><strong>Description:</strong> <?php echo htmlspecialchars($campaign['description']); ?></p>
            <p><strong>Start Date:</strong> <?php echo htmlspecialchars($campaign['start_date']); ?></p>
            <p><strong>End Date:</strong> <?php echo htmlspecialchars($campaign['end_date']); ?></p>
            <p><strong>Goal Amount:</strong> $<?php echo number_format($campaign['goal_amount'], 2); ?></p>
            <p><strong>Amount Raised:</strong> $<?php echo number_format($campaign['raised_amount'], 2); ?></p>
        </div>

        <div class="donate-form">
            <form action="campaign.php?id=<?php echo $campaign_id; ?>" method="POST">
                <h3>Make a Donation</h3>
                <input type="number" name="amount" placeholder="Enter donation amount" required>
                <button type="submit">Donate</button>
            </form>
        </div>

        <div class="back-link">
            <a href="home.php">← Back to Campaigns</a>
        </div>
    </div>
</body>
</html>
