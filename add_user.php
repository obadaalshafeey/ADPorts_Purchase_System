
<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    http_response_code(403);
    echo "Access Denied";
    exit;
}

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $employee_number = trim($_POST["employee_number"] ?? "");
    $password = $_POST["password"] ?? "";
    $role = $_POST["role"] ?? "";
    $department = trim($_POST["department"] ?? "");

    $allowed_roles = [
            "employee",
            "shift_supervisor",
            "hod",
            "it_manager",
            "hr_manager",
            "purchasing",
            "general_manager",
            "admin"
    ];

    if (
            $name === "" ||
            $employee_number === "" ||
            $password === "" ||
            $department === "" ||
            !in_array($role, $allowed_roles, true)
    ) {
        $message = "Please enter valid employee information.";
        $message_type = "danger";

    } else {

        $check_sql = "SELECT id FROM users WHERE employee_number = ?";
        $check_stmt = mysqli_prepare($conn, $check_sql);

        mysqli_stmt_bind_param($check_stmt, "s", $employee_number);
        mysqli_stmt_execute($check_stmt);
        mysqli_stmt_store_result($check_stmt);

        $count = mysqli_stmt_num_rows($check_stmt);

        if ($count > 0) {

            $message = "Employee Number Already Exists";
            $message_type = "warning";

        } else {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users
                    (name, employee_number, password, role, department)
                    VALUES (?, ?, ?, ?, ?)";

            $stmt = mysqli_prepare($conn, $sql);

            mysqli_stmt_bind_param(
                    $stmt,
                    "sssss",
                    $name,
                    $employee_number,
                    $hashed_password,
                    $role,
                    $department
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "Employee Added Successfully";
                $message_type = "success";
            } else {
                $message = "Error Adding Employee";
                $message_type = "danger";
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

            <h3 class="mb-2">Add New Employee</h3>
            <p class="text-secondary mb-4">
                Enter the employee information below.
            </p>

            <?php if ($message != "") { ?>

                <div class="alert alert-<?php echo $message_type; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Employee Name</label>
                    <input type="text" name="name"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Employee Number</label>
                    <input type="text" name="employee_number"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>

                    <select name="role" class="form-select">
                        <option value="employee">Employee</option>
                        <option value="shift_supervisor">Shift Supervisor</option>
                        <option value="hod">Head of Department</option>
                        <option value="it_manager">IT Manager</option>
                        <option value="hr_manager">HR Manager</option>
                        <option value="purchasing">Purchasing</option>
                        <option value="general_manager">General Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label">Department</label>
                    <input type="text" name="department"
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-dark">
                    Add Employee
                </button>

                <a href="users.php" class="btn btn-light">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
