<?php
    session_start();
    require_once( '../check/database.php' );
    if (isset($_SESSION['username'])) {

    }
    else {
        echo "<script>location.href='login.php'</script>";
    }

    if (isset($_SESSION['username'])) {
        $isAdmin = $_SESSION['isAdmin'];
    }
    else {
        $isAdmin = -1;
    }

    if (isset($_POST['submitnew'])) {
    // Where the file is going to be stored
        $name = $_POST['dorayakiName'];
        $deskripsi = $_POST['deskripsi'];
        $harga = $_POST['harga'];
        $stock = $_POST['stok'];
        $target_dir = "./images/";
        $file = $_FILES['gambar']['name'];
        $path = pathinfo($file);
        $filename = $path['filename']."-".$name;
        $ext = $path['extension'];
        $temp_name = $_FILES['gambar']['tmp_name'];
        $path_filename_ext = $target_dir.$filename.".".$ext;
        $path_filename_ext1 = ".".$target_dir.$filename.".".$ext;
        addNewVar($name, $deskripsi, $harga, $stock,  $path_filename_ext);
        echo "<script>alert('Dorayaki varian baru berhasil ditambahkan!')</script>";
        

    
    // Check if file already exists
    if (file_exists($path_filename_ext)) {
        }else{
        move_uploaded_file($temp_name, $path_filename_ext1);
        }
        
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <!-- Custom StyleSheet -->
    <link rel="stylesheet" href="../css/header-user.css" />
    <link rel="stylesheet" href="../css/index.css" />
    <link rel="stylesheet" href="../css/addvar.css" />
    
    <title>Home</title>
  </head>
  <body onload="renderHeader(<?= $isAdmin?>, 0)">
    <div class="header">
        <div class="header-brand" onclick="goToHome()">
            Doradora
        </div>

        <div class="header-search">
            <form action="search-result.php" id="search-form" name="search-form" method="GET">
                <input type="text" name="search-query" placeholder="Cari dorayaki disini">
                <div class="search-icon" onclick="submitSearch()"><i class="fas fa-search"></i></div>
            </form>
        </div>

        <div id="header-user-admin"></div>

        <div class="header-user">
            <i class="fas fa-user"></i>
            <?php if (isset($_SESSION['username'])) {?>
                <p><?= $_SESSION['username'] ?></p>
            <?php } else { ?>
                <p>Guest</p>
            <?php } ?>
            
        </div>
        
        <div class="vr"></div>

        <?php 
            if(array_key_exists('logout-btn', $_POST)) {
                if (isset($_SESSION['username'])) {
                    $_SESSION = [];
                    session_unset();
                    session_destroy();
                    setcookie('TKN','',time() - 3600,'/');
                    header("Location: ../index.php");
                    exit;
                }
            }
            else if(array_key_exists('login-btn', $_POST)){
                header("Location: login.php");
                exit;
            }
        ?>

        <div class="login-logout">
            <form method="POST">
                <?php if (isset($_SESSION['username'])) {?>
                    <input type='submit' name='logout-btn' id='logout-btn' value="Log Out"/>
                <?php } else { ?>
                    <input type='submit' name='login-btn' id='login-btn' value="Log In" />
                <?php } ?>
            </form>
        </div>
    </div>
    <div class="addnew">
        <h1><a href=""></a></h1>
        <h2>ADD NEW VARIANT</h2>
        <div class="add-form">
            <form action="" method="POST" enctype="multipart/form-data">
                <p>dorayaki name</p>
                <input type="text" name="dorayakiName" placeholder="input dorayaki name"  required />
                <br />
                <br />
                <p>description</p>
                <input type="text" name="deskripsi" placeholder="input description"  required />
                <br />
                <br />
                <p>price</p>
                <input type="text" name="harga" placeholder="input price"  required />
                <br />
                <br />
                <p>stock</p>
                <input type="text" name="stok" placeholder="input stock available"  required />
                <br />
                <br />
                <p>photos</p>
                <input type="file" name="gambar" placeholder="" required />
                <br />
                <br />
                <div class="add-btn">
                    <input type="submit" value="add" name="submitnew">
                </div>
            </form>
        </div>
    </div>
    </body>
    <script src="../js/index.js"></script>
</html>
