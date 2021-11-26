<?php
    session_start();
    if (!isset($_SESSION['username'])) {

        echo "<script>location.href='login.php'</script>";
    }
?>

<?php require_once("../check/soap-checkreq.php"); 
            stokDariPabrik(SoapCheckRequest());
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <!-- Custom StyleSheet -->
    <link rel="stylesheet" href="../css/header-user.css" />
    <link rel="stylesheet" href="../css/request.css" />
    <?php if ($_SESSION['isAdmin'] == 1) {?>
        <title>Edit Dorayaki</title>
    <?php } else { ?>
        <title>Keranjang</title>
    <?php } ?>
    <?php $isAdmin = $_SESSION['isAdmin']; ?>
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
            
            require_once( '../check/database.php' );
            require_once("../check/soap-req.php");
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
             else if(array_key_exists('cart-quantity-check-btn', $_POST)){
                setQuantityCart($_SESSION['username'], $_POST['idItem'], $_POST['quantity']);
             }
             else if(array_key_exists('buy-btn', $_POST)){
                buyItemFromCart($_SESSION['username'], date("Y-m-d h:i:sa", strtotime("now")));
             }
             else if (array_key_exists('edit-check-btn', $_POST)){
                SoapRequest($_SESSION['username'], $_POST['edit-idItem'], $_POST["jumlah-request"]);
             }
             else if (array_key_exists('del-from-cart-btn', $_POST)){
                 delFromCart($_SESSION['username'], $_POST['del-cart-idItem']);
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

    <div class="cart-title">
    <?php if ($_SESSION['isAdmin'] == 1) {?>
        <p>Request Stock</p>
    <?php }  ?>
    </div>
    <div class="cart-container">
        <div class="cart-left">
        <?php if($_SESSION['isAdmin'] != 1) {?>

        <?php
            require_once( '../check/database.php' );
            $cartItem = getCartItem($_SESSION['username']);
        ?>
        <?php if (count($cartItem) == 0) {?>
        <div class="cart-product">
            <p>Keranjang kosong</p>
        </div>
        <?php } else {?>
            <?php for($i = 0; $i < count($cartItem); $i++) {?>
                <?php $item = findItemByID($cartItem[$i]["idItem"])?>
                <div class="cart-product">
                    <div class="cart-img-container">
                        <img src=<?= ".".$item[0]["gambar"]?> alt="foto dorayaki" />
                    </div>
                    <div class="cart-details-container">
                        <div class="cart-details">
                            <p><?= $item[0]["namaItem"]?></p>
                            <strong>Rp. <?= number_format($item[0]["harga"])?></strong>
                            <p>Stok: <?= $item[0]["stok"] ?></p>
                            <form method="POST">
                                <input type="hidden" name="idItem" value=<?= $item[0]["idItem"]?>>
                                <input id="cart-qty" type="number" name="quantity" min="1" value=<?= $cartItem[$i]["quantity"]?> max=<?= $item[0]["stok"]?>>
                                <button id="cart-quantity-check-btn" name="cart-quantity-check-btn" type="submit"><i class="fas fa-check"></i></button>
                            </form>
                        </div>
                        <form action="" method="POST">
                            <input type="hidden" name="del-cart-idItem" value=<?= $item[0]["idItem"]?>>
                            <button class="cart-del-btn" type="submit" name="del-from-cart-btn">
                                <i class="fas fa-trash-alt fa-2x"></i>
                            </button>
                        </form>
                        
                    </div>
                </div>
                <?php } ?>
            <?php } ?>
        <?php } else {?>
        <?php
            require_once( '../check/database.php' );
            require_once("../check/soap-list.php");
            $allItem = SoapList();
        ?>
        <?php for($i = 0; $i < count($allItem); $i++) {?>
            <div class="edit-product">
                <div class="edit-details-container">
                    <div class="edit-details">
                        <form method="POST">
                        <div class="edit-details-left">
                                <p><?= $allItem[$i]["nama"]?></p>
                            </div>
                            
                            <div class="edit-details-right">
                                <p>Jumlah :</p>
                                <input type="number" name="jumlah-request" id="edit-stok" required min="1"/>
                            </div>
                                
                            <div class="edit-check-btn">
                                <input type="hidden" name="edit-idItem" value=<?= $allItem[$i]["idItem"]?> key=<?= $allItem[$i]["idItem"]?> />
                                <button id="edit-check-btn" name="edit-check-btn"  type="submit">request</i></button>
                            </div>

                        </form>
                    </div>
                    <!-- <form action="" method="POST">
                        <input type="hidden" name="title" value="asdsa">
                        <button class="cart-del-btn" type="submit" name="delfromcart">
                            <i class="fas fa-trash-alt fa-2x"></i>
                        </button>
                    </form> -->
                    
                </div>
            </div>
        <?php } ?>
        <?php } ?>
        </div>

    <script src="../js/index.js"></script>
    <script>
        // const selectElement = document.querySelector('.edit-stok');

        // selectElement.addEventListener('change', (event) => {
        // const result = document.querySelector('.result');
        // result.textContent = `You like ${event.target.value}`;
        // });

        // const stok = document.getElementById('edit-stok');
        // const inputHandler = function(e) {
        //     console.log(e.target.value);
        // }
        // stok.addEventListener('change', inputHandler);

    </script>
</body>

</html>
