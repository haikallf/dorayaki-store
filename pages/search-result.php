<?php
    session_start();    
    if (isset($_SESSION['username'])) {
        $isAdmin = $_SESSION['isAdmin'];
    }
    else {
        $isAdmin = -1;
    }
    
    if (isset($_GET['search-query'])) {
        if (!(empty($_GET['search-query']))) {
            $query = $_GET['search-query'];
        }
        else {
            header("Location: ../index.php");
            exit;
        }
    }
    else {
        header("Location: ../index.php");
        exit;
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
    
    <div class="product-container">
        <div class="product" style="padding-top: 80px;">
            <?php 
                require_once('../check/database.php');
                if ($isAdmin == 1) {
                    $itemArray = filterAllItemByName($query); // ini harus beda antara admin dan user, kalau user load yg available aja
                }
                else {
                    $itemArray = filterAvailableItemByName($query);
                }
            ?>
            <?php if (count($itemArray) > 0) {?>
                <?php for($i = 0; $i < count($itemArray); $i++) {?>
                    <form action="product-details.php" method="GET" name="itemForm" id="itemForm-<?=$i?>" class="itemForm">
                        <div class="product-card" onclick="submitData(<?=$i?>)">
                            <img src=<?= '.'.$itemArray[$i]["gambar"]?> alt="Gambar tidak tersedia.">
                            <p><?= $itemArray[$i]["namaItem"]?></p>
                            <p>★★★★★</p>
                            <p>Rp. <?= number_format($itemArray[$i]["harga"])?></p>
                            <input type="hidden" name="idItem" value=<?= $itemArray[$i]["idItem"]?>>
                            <!-- <input type="submit" name="" id="submit" value="gas"> -->
                        </div>
                    </form>
                <?php } ?>
            <?php } else {?>
                    <p>Tidak ada produk yang tersedia</p>
            <?php } ?>
        </div>
    </div>
    
    <div class="container" id="container"></div>
    <script src="../js/index.js"></script>
  </body>
</html>
