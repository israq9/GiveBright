<?php
session_start();
include 'dbconnect.php';

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Redirect to admin login if not logged in
if (!isset($_SESSION['admin_loggedin']) || $_SESSION['admin_loggedin'] !== true) {
    header("location: admin_login.php");
    exit;
}

// Handle actions (edit, delete, create)
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Edit campaign
    if ($action === 'edit') {
        $campaign_id = $_POST['campaign_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $goal_amount = $_POST['goal_amount'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $keyword = $_POST['keyword'];

        $sql = "UPDATE campaigns SET name = ?, description = ?, goal_amount = ?, start_date = ?, end_date = ?, keyword = ? WHERE campaign_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsssi", $name, $description, $goal_amount, $start_date, $end_date, $keyword, $campaign_id);

        if ($stmt->execute()) {
            $message = "Campaign updated successfully!";
        } else {
            $message = "Error updating campaign: " . $stmt->error;
        }
        $stmt->close();
    }

    // Delete campaign
    if ($action === 'delete') {
        $campaign_id = $_POST['campaign_id'];
        $sql = "DELETE FROM campaigns WHERE campaign_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $campaign_id);

        if ($stmt->execute()) {
            $message = "Campaign deleted successfully!";
        } else {
            $message = "Error deleting campaign: " . $stmt->error;
        }
        $stmt->close();
    }

    // Create a new campaign
    if ($action === 'create') {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $goal_amount = $_POST['goal_amount'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $keyword = $_POST['keyword'];
        $image = null;

        // Handle image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $image = 'uploads/' . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $image);
        }

        $sql = "INSERT INTO campaigns (name, description, goal_amount, start_date, end_date, keyword, image) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdssss", $name, $description, $goal_amount, $start_date, $end_date, $keyword, $image);

        if ($stmt->execute()) {
            $message = "Campaign created successfully!";
        } else {
            $message = "Error creating campaign: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch campaigns for listing
$result = $conn->query("SELECT * FROM campaigns");

if (!$result) {
    die("Database Query Failed: " . $conn->error); // Proper error handling for the query
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .nav-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            color: white;
            padding: 10px 20px;
        }

        .nav-bar h1 {
            margin: 0;
            font-size: 1.5rem;
        }

        .nav-bar a {
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 1rem;
            padding: 5px 10px;
            border: 1px solid transparent;
            border-radius: 5px;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .nav-bar a:hover {
            background-color: white;
            color: #333;
            border-color: white;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #f4f4f4;
        }

        .message {
            color: green;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .create-campaign, .edit-campaigns {
            margin-bottom: 20px;
        }

        .create-campaign label, .create-campaign input, .create-campaign textarea, .create-campaign select {
            display: block;
            margin-bottom: 10px;
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
    </style>
</head>
<body>
    <div class="nav-bar">
        <h1>Admin Dashboard</h1>
        <a href="admin_logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Admin Dashboard</h1>
        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <div class="create-campaign">
            <h2>Create a New Campaign</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create">
                <label for="name">Campaign Name:</label>
                <input type="text" id="name" name="name" required>

                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="goal_amount">Goal Amount:</label>
                <input type="number" id="goal_amount" name="goal_amount" step="0.01" required>

                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>

                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>

                <label for="keyword">Keyword:</label>
                <select id="keyword" name="keyword" required>
                    <option value="natural disaster">Natural Disaster</option>
                    <option value="ill health">Ill Health</option>
                    <option value="food crisis">Food Crisis</option>
                </select>

                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*">

                <button type="submit">Create Campaign</button>
            </form>
        </div>

        <h2>Edit or Delete Existing Campaigns</h2>
        <table>
            <thead>
                <tr>
                    <th>Campaign Name</th>
                    <th>Description</th>
                    <th>Goal Amount</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Keyword</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form action="" method="POST">
                            <td><input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required></td>
                            <td><textarea name="description" required><?php echo htmlspecialchars($row['description']); ?></textarea></td>
                            <td><input type="number" name="goal_amount" value="<?php echo htmlspecialchars($row['goal_amount']); ?>" required></td>
                            <td><input type="date" name="start_date" value="<?php echo htmlspecialchars($row['start_date']); ?>" required></td>
                            <td><input type="date" name="end_date" value="<?php echo htmlspecialchars($row['end_date']); ?>" required></td>
                            <td>
                                <select name="keyword" required>
                                    <option value="natural disaster" <?php echo ($row['keyword'] === 'natural disaster') ? 'selected' : ''; ?>>Natural Disaster</option>
                                    <option value="ill health" <?php echo ($row['keyword'] === 'ill health') ? 'selected' : ''; ?>>Ill Health</option>
                                    <option value="food crisis" <?php echo ($row['keyword'] === 'food crisis') ? 'selected' : ''; ?>>Food Crisis</option>
                                </select>
                            </td>
                            <td>
                                <input type="hidden" name="campaign_id" value="<?php echo $row['campaign_id']; ?>">
                                <input type="hidden" name="action" value="edit">
                                <button type="submit">Save</button>
                                <button type="submit" name="action" value="delete" onclick="return confirm('Are you sure you want to delete this campaign?');">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
