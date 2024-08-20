<?php
session_start();
include 'db.php';

// Clear the cart session
unset($_SESSION['cart']);

// Optionally reset other session data if needed
unset($_SESSION['username']);
unset($_SESSION['user_id']);

// Redirect to homepage
header('Location: index.php');
exit();
?>