
<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
    http_response_code(403);
    echo "Access Denied";
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo "Invalid Employee ID";
    exit;
}

$sql = "SELECT id, name, employee_number, department
        FROM users WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Employee Not Found";
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $employee_number = trim($_POST["employee_number"] ?? "");
    $department = trim($_POST["department"] ?? "");

    // Keep entered values in the form
    $user["name"] = $name;
    $user["employee_number"] = $employee_number;
    $user["department"] = $department;

    if ($name === "" || $employee_number === "" || $department === "") {

        $message = "All fields are required.";

    } else {

        // Check duplicate employee number
        $check_sql = "SELECT id FROM users
                      WHERE employee_number = ? AND id != ?";

        $check_stmt = mysqli_prepare($conn, $check_sql);
        mysqli_stmt_bind_param($check_stmt, "si", $employee_number, $id);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        if (mysqli_stmt_num_rows($check_stmt) > 0) {

            $message = "Employee Number Already Exists.";

        } else {

            $sql = "UPDATE users
                    SET name = ?, employee_number = ?, department = ?
                    WHERE id = ?";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                    $stmt,
                    "sssi",
                    $name,
                    $employee_number,
                    $department,
                    $id
            );

            if (mysqli_stmt_execute($stmt)) {
                header("Location: users.php");
                exit;
            } else {
                $message = "Error Updating Employee.";
            }
        }
    }
}

?>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<div class="container py-5" style="max-width: 650px;">

    <div class="card shadow-sm border-0">

        <div class="card-body p-4">

            <h3 class="mb-2">Edit Employee</h3>

            <p class="text-secondary mb-4">
                Update employee information below.
            </p>

            <?php if ($message != "") { ?>

                <div class="alert alert-secondary">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Employee Name</label>

                    <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?php echo htmlspecialchars($user['name']); ?>"
                            required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label">Employee Number</label>

                    <input
                            type="text"
                            name="employee_number"
                            class="form-control"
                            value="<?php echo htmlspecialchars($user['employee_number']); ?>"
                            required
                    >
                </div>

                <div class="mb-4">
                    <label class="form-label">Department</label>

                    <input
                            type="text"
                            name="department"
                            class="form-control"
                            value="<?php echo htmlspecialchars($user['department']); ?>"
                            required
                    >
                </div>

                <button type="submit" class="btn btn-dark">
                    Save Changes
                </button>

                <a href="users.php" class="btn btn-light">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
