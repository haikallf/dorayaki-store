<?php 
session_start();

$db = "../db/database.db";
$username = isset($_POST['u']) ? $_POST['u'] : '';
$password = isset($_POST['p']) ? $_POST['p'] : '';

$ok = true;
$message = array();

if (!isset($username) || empty($username) || !isset($password) || empty($password)) {
    $ok = false;
    $message[] = "Username dan password tidak boleh kosong.";
}

if ($ok) {
    // cari username di database
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM user WHERE username = '$username'");
    $data = $query->fetchArray(SQLITE3_ASSOC);

    if (empty($data)) {
        $ok = false;
        $message[] = "Username/password salah.";
    } else {
        $hashedPwd = $data["password"];
        if (!(password_verify($password, $hashedPwd))) {
            $ok = false;
            $message[] = "Username/password salah";
        } 
        else {
            $message[] = "Selamat datang!";
            // session
            $_SESSION['username'] = $username;
            $_SESSION['isAdmin'] = $data['isAdmin'];
            // cookies
            $time = time() + (3600);
            setcookie("login","login",0,'../');
            // hash token
            $hashUsername = hash('sha256', $username);
            setcookie("TKN",$hashUsername,$time,'/');
        }
    }
}
echo json_encode(
    array(
        'ok' => $ok,
        'message' => $message
    )
)

?>
