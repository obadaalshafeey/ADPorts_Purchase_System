

<?php

session_start();
require_once "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = (int) $_SESSION["user_id"];

$sql = "SELECT id, item_name, quantity, status, created_at
        FROM purchase_requests
        WHERE user_id = ?
        ORDER BY created_at DESC, id DESC";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

?>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<div class="container py-5">

    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>
            <h3>My Purchase Requests</h3>
            <p class="text-secondary">
                View and track your submitted requests.
            </p>
        </div>

        <a href="create_request.php" class="btn btn-dark">
            New Request
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0) { ?>

                        <?php while ($request = mysqli_fetch_assoc($result)) { ?>

                            <tr>
                                <td>
                                    #<?php echo (int) $request["id"]; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($request["item_name"]); ?>
                                </td>

                                <td>
                                    <?php echo (int) $request["quantity"]; ?>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        <?php
                                        echo htmlspecialchars(
                                            ucwords(
                                                str_replace(
                                                    "_",
                                                    " ",
                                                    $request["status"]
                                                )
                                            )
                                        );
                                        ?>
                                    </span>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($request["created_at"]); ?>
                                </td>
                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>
                            <td colspan="5" class="text-center text-secondary py-4">
                                No purchase requests found.
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
