<?php 
session_start();
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
    <link rel="stylesheet" href="../css/login-signup.css" />
    <title>Login - Doradora</title>
</head>

<body>
    <div class="login">
        <h1><a href="../index.php">Doradora</a></h1>
        <h2>LOG IN</h2>
        <div class="login-form">
            <form action="" method="POST">
                <ul id="form-messages"></ul>
                <p>Username</p>
                <input id="username" type="text" name="username" placeholder="Type your email" />
                <br />
                <br />
                <p>Password</p>
                <input id="password" type="password" name="password" placeholder="Type your password ">
                <br />
                <br />
                <div class="login-btn">
                    <input type="button" value="Login" id="submit" name="submit">
                </div>
            </form>
        </div>

        <div class="login-alt">
            <p>Or login with </p>
            <div class="login-alt-icon-container">
                <div class="apple-icon">
                    <i class="fab fa-apple fa-2x"></i>
                </div>

                <div class="facebook-icon">
                    <i class="fab fa-facebook-f fa-2x"></i>
                </div>

                <div class="google-icon">
                    <i class="fab fa-google fa-2x"></i>
                </div>

                <div class="twitter-icon">
                    <i class="fab fa-twitter fa-2x"></i>
                </div>
            </div>
        </div>

        <p>Not a member? <a href="signup.php">Sign Up</a></p>
    </div>
    <!-- <?php 
        if (array_key_exists("submit",$_POST)) {
            if (isset($_POST["username"]) && isset($_POST["password"]) && (!(empty($_POST["username"]))) && (!(empty($_POST["password"])))) {
               
            }
        }
    ?> -->
    <script>
        const form = {
        u: document.getElementById('username'),
        p: document.getElementById('password'),
        }
        document.getElementById('submit').addEventListener('click', validasiData);

        function validasiData() {
            const ajax = new XMLHttpRequest();
            ajax.onload = function () {
                var items = ajax.responseText;
                items = JSON.parse(items);
                console.log(items);
                if (items.ok) {
                    location.href = '../index.php';
                }
                else {
                    var list = document.getElementById("form-messages");
                    while (list.hasChildNodes()) {
                        list.removeChild(list.firstChild);
                    }
                    items.message.forEach((message) => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        list.appendChild(li);
                    });
                    list.style.display = "block";
                }
            }
            const data = "u="+form.u.value+"&p="+form.p.value;
            ajax.open("POST", "../check/check-login.php", true);
            ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax.withCredentials = true;
            ajax.send(data);
        }
    </script>
</body>
</html>
