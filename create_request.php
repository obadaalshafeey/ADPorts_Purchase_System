
<?php

session_start();
include "db.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$message = "";
$error = "";

$item_name = "";
$quantity = "";
$description = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $item_name = trim($_POST["item_name"] ?? "");
    $quantity = trim($_POST["quantity"] ?? "");
    $description = trim($_POST["description"] ?? "");

    if ($item_name === "" || $quantity === "") {

        $error = "Please fill in all required fields.";

    } elseif (strlen($item_name) > 255) {

        $error = "Item name is too long.";

    } elseif (
        filter_var(
            $quantity,
            FILTER_VALIDATE_INT,
            ["options" => ["min_range" => 1]]
        ) === false
    ) {

        $error = "Quantity must be a positive whole number.";

    } else {

        $user_id = (int) $_SESSION["user_id"];
        $quantity = (int) $quantity;

        $sql = "INSERT INTO purchase_requests
                (user_id, item_name, quantity, description, status)
                VALUES (?, ?, ?, ?, 'pending_supervisor')";

        $stmt = mysqli_prepare($conn, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "isis",
            $user_id,
            $item_name,
            $quantity,
            $description
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Purchase request submitted successfully.";

            $item_name = "";
            $quantity = "";
            $description = "";

        } else {

            $error = "Error submitting purchase request.";
        }
    }
}

?>

<?php include "header.php"; ?>
<?php include "navbar.php"; ?>

<div class="container py-5" style="max-width: 700px;">

    <div class="card border-0 shadow-sm">

        <div class="card-body p-4">

            <h3 class="mb-2">New Purchase Request</h3>

            <p class="text-secondary mb-4">
                Fill in the details to submit a purchase request.
            </p>

            <?php if ($message !== "") { ?>

                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php } ?>

            <?php if ($error !== "") { ?>

                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>

            <?php } ?>

            <form method="POST">

                <div class="mb-3">

                    <label for="item_name" class="form-label">
                        Item Name
                    </label>

                    <input
                        type="text"
                        id="item_name"
                        name="item_name"
                        class="form-control"
                        maxlength="255"
                        value="<?php echo htmlspecialchars($item_name); ?>"
                        required
                    >

                </div>

                <div class="mb-3">

                    <label for="quantity" class="form-label">
                        Quantity
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        name="quantity"
                        class="form-control"
                        min="1"
                        step="1"
                        value="<?php echo htmlspecialchars((string) $quantity); ?>"
                        required
                    >

                </div>

                <div class="mb-4">

                    <label for="description" class="form-label">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        rows="4"
                    ><?php echo htmlspecialchars($description); ?></textarea>

                </div>

                <button type="submit" class="btn btn-dark">
                    Submit Request
                </button>

                <a href="dashboard.php" class="btn btn-light">
                    Cancel
                </a>

            </form>

        </div>

    </div>

</div>

<?php include "footer.php"; ?>
