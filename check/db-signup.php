<?php 
session_start();
$db = "../db/database.db";
// regex email dan username dan cek username unik
$username = isset($_POST['u']) ? $_POST['u'] : ''; 
$password = isset($_POST['p']) ? $_POST['p'] : ''; 
$email = isset($_POST['e']) ? $_POST['e'] : '';

$ok = true;
$message = array();

if (!isset($username) || empty($username) || !isset($password) || empty($password) || !isset($email) || empty($email)) {
    $ok = false;
    $message[] = "Jangan ada kolom yang kosong.";
}

if ($ok) {

    // enkripsi password, masukin ke database
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $db = new SQLite3($GLOBALS['db']);
    $query_insert = $db->query("INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashed');");
    $message[] = "Akun berhasil mendaftar. Selamat berbelanja!";

    $_SESSION['username'] = $username;
    $_SESSION['isAdmin'] = $data['isAdmin'];
    // cookies
    $time = time() + (3600);
    setcookie("login","login",0,'../');
    // hash token
    $hashUsername = hash('sha256', $username);
    setcookie("TKN",$hashUsername,$time,'/');

}

echo json_encode(
    array(
        'ok' => $ok,
        'message' => $message
    )
)

?>