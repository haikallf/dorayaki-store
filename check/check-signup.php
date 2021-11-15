<?php 
session_start();
$db = "../db/database.db";
// regex email dan username dan cek username unik
$username = isset($_POST['u']) ? $_POST['u'] : ''; 
$password = isset($_POST['p']) ? $_POST['p'] : ''; 
$email = isset($_POST['e']) ? $_POST['e'] : '';

$ok = true;
$message = array();

// periksa username
if ($ok) {
    // periksa username regex
    $regex = "/^[a-zA-Z0-9._]+$/";
    if (!preg_match($regex, $username)) {
        $ok = false;
        $message[] = "Username kosong/tidak valid.";
    }   
    // periksa username dan email sudah ada atau belum di db
    $db = new SQLite3($GLOBALS['db']);
    $query = $db->query("SELECT * FROM user WHERE username = '$username' OR email = '$email'");
    $data = $query->fetchArray(SQLITE3_ASSOC);

    if (!empty($data)) {
        $ok = false;
        $message[] = "Username/email telah terdaftar.";
    }
}
if ($ok) {
    // periksa email
    if(!(filter_var($email, FILTER_VALIDATE_EMAIL))) {
        $ok = false;
        $message[] = "Email kosong/tidak valid.";
    }
    else {
        $message[] = "Username & email tersedia.";
    }
    
}

echo json_encode(
    array(
        'ok' => $ok,
        'message' => $message
    )
)
?>