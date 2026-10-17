
<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$name = $_SESSION["name"];
$role = $_SESSION["role"];

?>

<?php include "header.php"; ?>

<?php include "navbar.php"; ?>

<div class="container mt-5">

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <h2 class="text-primary">
                Welcome, <?php echo htmlspecialchars($name); ?>
            </h2>

            <p class="text-muted">
                Welcome to AD Ports Purchase System.
            </p>

            <hr>

            <h5>Employee Information</h5>

            <p>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($name); ?>
            </p>

            <p>
                <strong>Role:</strong>
                <span class="badge bg-primary">
                    <?php echo htmlspecialchars($role); ?>
                </span>
            </p>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
