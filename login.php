
<?php

session_start();

include "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";
$employee_number = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $employee_number = trim($_POST["employee_number"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($employee_number === "" || $password === "") {

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

<?php include "header.php"; ?>

<div class="container d-flex align-items-center justify-content-center"
     style="min-height: 100vh;">

    <div class="card border-0 shadow-sm w-100"
         style="max-width: 420px;">

        <div class="card-body p-4 p-md-5">

            <div class="text-center mb-4">

                <h3 class="fw-bold mb-2">
                    AD Ports
                </h3>

                <p class="text-secondary mb-0">
                    Purchase Management System
                </p>

            </div>

            <h5 class="mb-2">Login</h5>

            <p class="text-secondary small mb-4">
                Enter your employee number and password.
            </p>

            <?php if ($error !== "") { ?>

                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label for="employee_number" class="form-label">
                        Employee Number
                    </label>

                    <input
                            type="text"
                            id="employee_number"
                            name="employee_number"
                            class="form-control"
                            value="<?php echo htmlspecialchars($employee_number); ?>"
                            autocomplete="username"
                            required
                    >

                </div>

                <div class="mb-4">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-control"
                            autocomplete="current-password"
                            required
                    >

                </div>

                <button type="submit"
                        class="btn btn-dark w-100">
                    Login
                </button>

            </form>

            <p class="text-center text-secondary small mt-4 mb-0">
                AD Ports Purchase System
            </p>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
