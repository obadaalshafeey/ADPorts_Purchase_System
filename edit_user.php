<?php


session_start();

include "db.php";


if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION["role"]) || $_SESSION["role"] != "admin") {
    echo "Access Denied";
    exit;
}

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo "Invalid Employee ID";
    exit;
}
$sql = "SELECT id, name, employee_number, role, department FROM users WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "Employee Not Found";
    exit;
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $employee_number = trim($_POST["employee_number"]);
    $department = trim($_POST["department"]);

    if ($name == "" || $employee_number == "" || $department == "") {
        echo "All fields are required";
        exit;
    }

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
        echo "Error Updating Employee";
    }
}

?>
<h2>Edit Employee</h2>

<form method="POST">

    <label>Employee Name:</label>
    <input
        type="text"
        name="name"
        value="<?php echo htmlspecialchars($user['name']); ?>"
    >

    <br><br>

    <label>Employee Number:</label>
    <input
        type="text"
        name="employee_number"
        value="<?php echo htmlspecialchars($user['employee_number']); ?>"
    >

    <br><br>

    <label>Department:</label>
    <input
        type="text"
        name="department"
        value="<?php echo htmlspecialchars($user['department']); ?>"
    >

    <br><br>

    <button type="submit">Save Changes</button>

</form>

