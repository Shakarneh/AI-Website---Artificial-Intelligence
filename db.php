<?php
$conn = mysqli_connect('sql311.infinityfree.com', 'if0_42011053', 'pKQ5JZM3J3zUPn', 'if0_42011053_lab4');
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');
?>