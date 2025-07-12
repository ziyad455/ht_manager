<?php
session_start();

if (isset($_SESSION['admin']) && $_SESSION['admin'] === true) {
    header("Location: frontend/admin/main.php");
    exit();

} elseif (isset($_SESSION['user'])) {
    header("Location: frontend/user/Dashboard.php");
    exit();

} else {
    header("Location: frontend/core/guist.php");
    exit();
}
