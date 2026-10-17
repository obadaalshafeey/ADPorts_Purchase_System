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

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "Invalid Request";
    exit;
}

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    echo "Invalid Employee ID";
    exit;
}

if ($id == $_SESSION["user_id"]) {
    echo "You Cannot Delete Your Own Account";
    exit;
}

$sql = "DELETE FROM users WHERE id = ?";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: users.php");
    exit;
} else {
    echo "Error Deleting Employee";
}

