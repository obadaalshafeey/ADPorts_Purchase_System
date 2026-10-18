
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo "Access Denied";
    exit;
}

$name = $_SESSION["name"];

?>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<div class="container py-5">

    <h3>Admin Dashboard</h3>

    <p class="text-secondary mb-4">
        Welcome, <?php echo htmlspecialchars($name); ?>
    </p>

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h5>Employee Management</h5>

            <p class="text-secondary">
                Manage employee information and accounts.
            </p>

            <a href="users.php" class="btn btn-dark">
                View Employees
            </a>

            <a href="add_user.php" class="btn btn-outline-secondary">
                Add Employee
            </a>

        </div>

    </div>

    <a href="dashboard.php"
       class="d-inline-block mt-4 text-secondary text-decoration-none">
        Back to Dashboard
    </a>

</div>

<?php include "footer.php"; ?>
