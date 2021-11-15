<?php 
$regex = "/^[a-zA-Z0-9._]+$/";
$username = "sa";
$message = array();
$db = new SQLite3("./db/database.db");
$query = $db->query("SELECT * FROM user WHERE username = '$username'");
$data = $query->fetchArray(SQLITE3_ASSOC);

if (!empty($data)) {
    $ok = false;
    $message[] = "Username telah terdaftar.";
}
else {
    $message[] = "Akun berhasil mendaftar. Selamat berbelanja!";
}
var_dump($message);
?>