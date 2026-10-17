<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    echo "Access Denied";
    exit;
}
?>
<!DOCTYPE html>
<h2>Admin Dashboard</h2>
<a href="users.php">View Employees</a>
<h3>Welcome <?php echo htmlspecialchars($_SESSION["name"]); ?></h3>

<a href="dashboard.php">Back to Dashboard</a>
