
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

$sql = "SELECT id, name, employee_number, role, department FROM users";

$result = mysqli_query($conn, $sql);

?>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<div class="container mt-5">

    <div class="card shadow-sm">

        <div class="card-body p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h2 class="mb-0">Employees List</h2>

                <a href="add_user.php" class="btn btn-primary">
                    + Add Employee
                </a>

            </div>

            <div class="table-responsive">

                <table class="table table-striped table-hover align-middle">

                    <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Employee Number</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                    </thead>

                    <tbody>

                    <?php while ($user = mysqli_fetch_assoc($result)) { ?>

                        <tr>

                            <td><?php echo htmlspecialchars($user["id"]); ?></td>

                            <td><?php echo htmlspecialchars($user["name"]); ?></td>

                            <td><?php echo htmlspecialchars($user["employee_number"]); ?></td>

                            <td>
                                    <span class="badge bg-secondary">
                                        <?php echo htmlspecialchars($user["role"]); ?>
                                    </span>
                            </td>

                            <td><?php echo htmlspecialchars($user["department"]); ?></td>

                            <td>

                                <div class="d-flex gap-2">

                                    <a
                                            href="edit_user.php?id=<?php echo urlencode($user['id']); ?>"
                                            class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <form
                                            action="delete_user.php"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this employee?');"
                                    >

                                        <input
                                                type="hidden"
                                                name="id"
                                                value="<?php echo htmlspecialchars($user['id']); ?>"
                                        >

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
