<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;700;800;900&display=swap"
        rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <!-- Custom StyleSheet -->
    <link rel="stylesheet" href="../css/header-user.css" />
    <link rel="stylesheet" href="../css/product-details.css" />
    <title>Produk</title>
</head>
    <?php
    require_once( '../check/database.php' );
    session_start();
    syncStockAndQuantity();
    if(array_key_exists('add-to-cart', $_POST)) {
        if (isset($_SESSION['username']) && isset($_POST["quantity-hidden"])) {
            if(isset($_POST["idItem"])){
                addToCart($_SESSION["username"], $_POST["idItem"], $_POST["quantity-hidden"]);
            }
        }
        else { //pindah ke halaman login
            header("Location: login.php");
        }
    }

    else if (array_key_exists('delete-item', $_POST)) {
        if (!(isset($_SESSION['username']))) {
            echo "<script>alert('Silahkan login terlebih dahulu.');</script>";
            header("Location: login.php");
        }
        else {
            if ($_SESSION["isAdmin"]) {
                deleteItem($_POST['idItem']);
                echo "<script>alert('Penghapusan dorayaki berhasil!');</script>";
            }
            else {
                // kalau user?
            }
        }
    }
    else if (array_key_exists('available-item', $_POST)) {
        if (!(isset($_SESSION['username']))) {
            echo "<script>alert('Silahkan login terlebih dahulu.');</script>";
            header("Location: login.php");
        }
        else {
            if ($_SESSION["isAdmin"]) {
                setAvailable($_POST['idItem']);
                echo "<script>alert('Dorayaki tersedia!');</script>";
            }
            else {
                // kalau user?
            }
        }
    }
    else if (array_key_exists('buy-single-item-btn', $_POST)){
        if (isset($_SESSION["username"])) {
        buyItem($_SESSION["username"], date("Y-m-d h:i:sa", strtotime("now")), $_POST['idItem'], (int) $_POST["quantity-hidden"]);
        }
        else {
            echo "<script>location.href='login.php'</script>";
        }
    }

    $isAdmin = isset($_SESSION['username']) ? $_SESSION['isAdmin'] : -1;
    $id = isset($_GET["idItem"]) ? $_GET["idItem"] : -1;
    ?>

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

    <div class="product-details">
        <div class="product-left">
            <img id="gambar" src="" alt="Gambar tidak tersedia."/>
        </div>
        <div class="product-right">
            <div class="product-right-title">
                <h3 id="namaItem"></h3>
                <p>★★★★★</p>
                <p id="harga"></p>
                <p id="stok"></p>
                <p id="terjual"></p>
                <p id="status"></p>
            </div>
            <div class="product-right-description">
                <h3>Deskripsi</h3>
                <p id="deskripsi"></p>
            </div>
            <?php if ($isAdmin == 0) {?>
                <div class="product-right-quantity">
                    <h4>Jumlah: </h4>
                    <form method="POST">
                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown()"><i class="fas fa-minus fa-sm"></i></button>
                        <input type="number" id="quantity" name="quantity" min=1 value=1>
                        <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp()"><i class="fas fa-plus fa-sm"></i></button>
                    </form>
                </div>
            <?php } ?>
            <div class="product-right-button">
                <?php if ($isAdmin != 1) {?>
                    <div class="product-right-button-primary">
                        <form method="POST">
                            <input type="hidden" name="idItem" value=<?= $id ?> />
                            <input type="hidden" name="quantity-hidden" id="quantity-hidden"/>
                            <div class="add-to-cart">
                                <button type="submit" name="add-to-cart"><i class="fas fa-shopping-cart"></i> +KERANJANG</button>
                            </div>
                        </form>
                        <form method="POST">
                            <div class="buy-now">
                                <input type="hidden" name="idItem" value=<?= $id ?> />
                                <input type="hidden" name="quantity-hidden" id="quantity-hidden" value=1/>
                                <button type="submit" name="buy-single-item-btn" id="buy-single-item-btn"><i class="fas fa-wallet"></i> BELI SEKARANG</button>
                            </div>
                        </form>
                            
                    </div>
                <?php } else {?>
                    <div class="product-right-button-primary">
                        <form method="POST">
                            <input type="hidden" name="idItem" value=<?=$id ?> />
                            <?php $item = findItemById($id)?>
                            <?php if ($item[0]["available"] == 1) {?>
                                <div class="delete-item">
                                    <button type="submit" name="delete-item"><i class="fas fa-backspace"></i> HAPUS DORAYAKI</button>
                                </div>
                            <?php } else { ?>
                                <div class="available-item">
                                    <button type="submit" name="available-item"><i class="fas fa-check"></i> BUAT TERSEDIA</button>
                                </div>
                            <?php }?>
                        </form>

                        <form action="cart.php" method="POST">
                            <div class="edit-item">
                                <button type="submit" name="edit-item"><i class="fas fa-pen"></i> EDIT DORAYAKI</button>
                            </div>
                        </form>
                    </div>
                <?php } ?>
                
                <div class="product-right-button-secondary">
                    <div class="wishlist-button">
                        <button><i class="fas fa-heart"></i> Wishlist</button>
                    </div>
                    <div class="chat-button">
                        <button><i class="fas fa-comment-dots"></i> Chat</button>
                    </div>
                    <div class="share-button">
                        <button><i class="fas fa-share-alt"></i> Share</button>
                    </div>
                </div>
            </div>
            <div class="hr">
                <hr />
            </div>
            <div class="product-right-delivery">
                <h3>Pengiriman</h3>
                <div class="product-right-delivery-location">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>Dikirim dari <b>Jakarta Pusat</b></p>
                </div>
                <div class="product-right-delivery-cost">
                    <i class="fas fa-truck"></i>
                    <p>Ongkir Reguler Rp. 19,000</p>
                </div>
                <p>Estimasi tiba 1 September 2021</p>
                <a href="#">Lihat pilihan kurir</a>
            </div>
        </div>
    </div>
    <script>
        id = <?=$id ?>;
        loadItem();
        setInterval(() => {
            loadItem();
        }, 5000);
        function loadItem() {
            const ajax = new XMLHttpRequest();
            ajax.onload = function () {
                var response = ajax.responseText;
                response = JSON.parse(response);
                const items = response.all;
                const terjual = (response.sold != null) ? response.sold : 0;
                document.getElementById("gambar").src = '.'+items[0]["gambar"];
                document.getElementById("namaItem").innerHTML = items[0]["namaItem"];
                document.getElementById("harga").innerHTML = "Rp. " + items[0]["harga"].toLocaleString("en-US");
                document.getElementById("stok").innerHTML = "Stok : " + items[0]["stok"];
                document.getElementById("deskripsi").innerHTML = items[0]["deskripsi"];
                document.getElementById("terjual").innerHTML = "Terjual : "+terjual;
                document.getElementById("status").innerHTML = (items[0]["available"] == 1) ? "Status : Tersedia" : "Status : Kosong";
                document.getElementById("quantity").setAttribute("max", items[0]["stok"]);
                +document.getElementById("quantity-hidden").setAttribute("value", +document.getElementById("quantity").value);
                // document.getElementById("quantity-hidden").setAttribute("value", document.getElementById("quantity-hidden").value.replace("/", ""));
                // document.getElementById("quantity-hidden").setAttribute("value", Number(document.getElementById("quantity-hidden").value, 10))
                console.log(document.getElementById("quantity-hidden").value);
            }
            
            ajax.open("GET", "../check/db-product-details.php?id="+id, true);
            ajax.send();

            const input = document.querySelector('input');
            const log = document.getElementById('quantity-hidden');

            input.addEventListener('input[name="quantity"]', updateValue);

            function updateValue(e) {
                log.updateValue = e.target.value;
                console.log(log)
            }
        }
    </script>

    <script src="../js/index.js"></script>
</body>

</html>