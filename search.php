<?php
require 'db.php';

// Get search term and filter from user input
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';

// Build SQL query
$sql = "SELECT * FROM campaigns WHERE (title LIKE :search OR description LIKE :search)";
$params = [':search' => '%' . $searchTerm . '%'];

if (!empty($filter)) {
    $sql .= " AND keyword = :filter";
    $params[':filter'] = $filter;
}

// Prepare and execute the query
$stmt = $pdo->prepare($sql);
$stmt->execute($params);

// Fetch results
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campaign Search</title>
</head>
<body>
    <h1>Search Campaigns</h1>
    <form method="get" action="search.php">
        <input type="text" name="search" placeholder="Search campaigns" value="<?php echo htmlspecialchars($searchTerm); ?>">
        <select name="filter">
            <option value="">All Categories</option>
            <option value="natural disaster" <?php echo $filter === 'natural disaster' ? 'selected' : ''; ?>>Natural Disaster</option>
            <option value="ill health" <?php echo $filter === 'ill health' ? 'selected' : ''; ?>>Ill Health</option>
            <option value="food crisis" <?php echo $filter === 'food crisis' ? 'selected' : ''; ?>>Food Crisis</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <h2>Results</h2>
    <?php if (!empty($results)): ?>
        <ul>
            <?php foreach ($results as $campaign): ?>
                <li>
                    <strong><?php echo htmlspecialchars($campaign['title']); ?></strong><br>
                    <?php echo htmlspecialchars($campaign['description']); ?><br>
                    <em>Category: <?php echo htmlspecialchars($campaign['keyword']); ?></em>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No campaigns found.</p>
    <?php endif; ?>
</body>
</html>
