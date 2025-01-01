<?php
include 'dbconnect.php';

if (isset($_GET['campaign_id'])) {
    $campaign_id = intval($_GET['campaign_id']);
    $sql = "DELETE FROM campaigns WHERE campaign_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $campaign_id);
        if ($stmt->execute()) {
            header("location: admin_dashboard.php");
            exit;
        } else {
            echo "Error deleting campaign: " . $conn->error;
        }
    }
}
?>
