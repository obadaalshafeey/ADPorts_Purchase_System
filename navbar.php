
<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">

    <div class="container">

        <a class="navbar-brand fw-bold" href="dashboard.php">
            AD Ports | Purchase System
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainNavbar"
            aria-controls="mainNavbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">

            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">
                        Dashboard
                    </a>
                </li>

                <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin") { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="admin.php">
                            Admin Panel
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="users.php">
                            Manage Users
                        </a>
                    </li>

                <?php } ?>

            </ul>

            <a href="logout.php" class="btn btn-outline-light">
                Logout
            </a>

        </div>

    </div>

</nav>
