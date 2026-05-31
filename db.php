<?php
$conn = mysqli_connect('sql208.infinityfree.com', 'if0_42063830', 'a1s1d1f1g1h1@@', 'if0_42063830_lab4_ai_site');
if (!$conn) {
    die('Connection failed: ' . mysqli_connect_error());
}
mysqli_set_charset($conn, 'utf8');
?>