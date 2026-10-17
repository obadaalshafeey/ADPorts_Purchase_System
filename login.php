<?php

session_start();

include "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_number = trim($_POST["employee_number"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($employee_number == "" || $password == "") {

        $error = "Please enter your employee number and password.";

    } else {

        $sql = "SELECT * FROM users WHERE employee_number = ?";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param($stmt, "s", $employee_number);

        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        $user = mysqli_fetch_assoc($result);

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["department"] = $user["department"];

            header("Location: dashboard.php");
            exit;

        } else {

            $error = "Invalid employee number or password.";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login | AD Ports Purchase System</title>
</head>

<body>

<h2>AD Ports Purchase System</h2>

<h3>Login</h3>

<?php if ($error != "") { ?>

    <p style="color: red;">
        <?php echo htmlspecialchars($error); ?>
    </p>

<?php } ?>

<form method="POST">

    <label>Employee Number:</label>
    <input
            type="text"
            name="employee_number"
            required
    >

    <br><br>

    <label>Password:</label>
    <input
            type="password"
            name="password"
            required
    >

    <br><br>

    <button type="submit">Login</button>

</form>

</body>

</html>