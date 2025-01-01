<?php
session_start();
include 'dbconnect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("location: signin.php");
    exit;
}

// Handle donations
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['campaign_id'], $_POST['amount'])) {
    $campaign_id = intval($_POST['campaign_id']);
    $amount = floatval($_POST['amount']);

    if ($amount > 0) {
        // Update the raised amount for the campaign
        $sql = "UPDATE campaigns SET raised_amount = raised_amount + ? WHERE campaign_id = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("di", $amount, $campaign_id);
            if ($stmt->execute()) {
                echo "<script>alert('Donation successful!');</script>";
            } else {
                echo "<script>alert('Failed to process the donation. Please try again.');</script>";
            }
            $stmt->close();
        } else {
            echo "<script>alert('Database error: " . $conn->error . "');</script>";
        }
    } else {
        echo "<script>alert('Invalid donation amount. Please enter a positive number.');</script>";
    }
}

// Handle search and filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? trim($_GET['filter']) : '';

$sql = "SELECT * FROM campaigns WHERE 1=1";
$params = [];
$types = '';

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = '%' . $search . '%';
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= 'ss';
}

if (!empty($filter)) {
    $sql .= " AND keyword = ?";
    $params[] = $filter;
    $types .= 's';
}

$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Campaigns</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles for the page */
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

        .progress-bar-container {
            background-color: #e0e0e0;
            border-radius: 20px;
            overflow: hidden;
            height: 20px;
            width: 100%;
            margin-top: 10px;
        }

        .progress-bar {
            height: 100%;
            background-color: #28a745;
            color: white;
            text-align: center;
            line-height: 20px;
            font-size: 14px;
        }

        .donate-form {
            margin-top: 15px;
        }

        input[type="text"], input[type="number"], select {
            padding: 8px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        button {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
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
		<a href="ending_soon_campaigns.php">Ending Soon</a>
        <a href="logout.php">Logout</a>
    </nav>
    <div class="container">
        <h1>Available Campaigns</h1>

        <!-- Search and Filter Form -->
        <form method="GET" action="home.php" style="margin-bottom: 20px; display: flex; gap: 10px;">
            <input type="text" name="search" placeholder="Search campaigns..." value="<?php echo htmlspecialchars($search); ?>" style="flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
            <select name="filter" style="padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="">All Types</option>
                <option value="natural disaster" <?php echo $filter === 'natural disaster' ? 'selected' : ''; ?>>Natural Disaster</option>
                <option value="ill health" <?php echo $filter === 'ill health' ? 'selected' : ''; ?>>Ill Health</option>
                <option value="food crisis" <?php echo $filter === 'food crisis' ? 'selected' : ''; ?>>Food Crisis</option>
            </select>
            <button type="submit" style="padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer;">
                Search
            </button>
        </form>

        <!-- Display campaigns -->
        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="campaign">
                    <img src="<?php echo htmlspecialchars($row['image'] ?? 'default.jpg'); ?>" alt="Campaign Image">
                    <div class="campaign-content">
                        <h3><a href="campaign.php?id=<?php echo $row['campaign_id']; ?>" target="_blank"><?php echo htmlspecialchars($row['name']); ?></a></h3>
                        <p><strong>Start Date:</strong> <?php echo htmlspecialchars($row['start_date']); ?></p>
                        <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['end_date']); ?></p>
                        <p><strong>Goal Amount:</strong> $<?php echo number_format($row['goal_amount'], 2); ?></p>
                        <p><strong>Amount Donated:</strong> $<?php echo number_format($row['raised_amount'], 2); ?></p>
                        <p><?php echo htmlspecialchars($row['description']); ?></p>
                        <div class="progress-bar-container">
                            <?php
                            $progress = min(100, ($row['raised_amount'] / $row['goal_amount']) * 100);
                            ?>
                            <div class="progress-bar" style="width: <?php echo $progress; ?>%;">
                                <?php echo round($progress); ?>%
                            </div>
                        </div>
                        <form action="home.php" method="POST" class="donate-form">
                            <input type="hidden" name="campaign_id" value="<?php echo $row['campaign_id']; ?>">
                            <input type="number" name="amount" placeholder="Enter donation amount" required>
                            <button type="submit">Donate</button>
                        </form>
                    </div>
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
