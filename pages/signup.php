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
    <title>Signup - Doradora</title>
</head>

<body>

    <div class="signup">
        <h1><a href="../index.php">Doradora</a></h1>
        <h2>SIGN UP FORM</h2>
        <div class="signup-form">
            <form action="" method="POST">
                <ul id="form-messages"></ul>
                <p>Email</p>
                <input type="email" id="email" name="email" placeholder="Type your email ">
                <br />
                <br />
                <p>Username</p>
                <input type="text" id="username" name="username" placeholder="Type your username" style="text-transform: lowercase"/>
                <br />
                <br />
                <p>Password</p>
                <input type="password" id="password" name="password" placeholder="Type your password ">
                <br />
                <br />
                <div id="signup-btn" class="signup-btn">
                    <input type="button" name='signup-btn' value="Signup">
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

        <p>Have an account? <a href="login.php">Log In</a> instead.</p>
    </div>
    <script>
        const form = {
        u: document.getElementById('username'),
        p: document.getElementById('password'),
        e: document.getElementById('email')
        }
        var valid = false;
        form.e.addEventListener('change', validasiData);
        form.u.addEventListener('change', validasiData);

        function validasiData() {
            const ajax1 = new XMLHttpRequest();
            ajax1.onload = function () {
                var items = ajax1.responseText;
                items = JSON.parse(items);

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
                if (items.ok) {
                    document.getElementById("signup-btn").addEventListener('click', insertData);
                    console.log(items);
                }
            }

            const data = "u="+form.u.value+"&p="+form.p.value+"&e="+form.e.value;
            ajax1.open("POST", "../check/check-signup.php", true);
            ajax1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax1.send(data);
        }
        
        function insertData() {
            const ajax1 = new XMLHttpRequest();
            ajax1.onload = function () {
                var items = ajax1.responseText;
                items = JSON.parse(items);
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

            const data = "u="+form.u.value+"&p="+form.p.value+"&e="+form.e.value;
            ajax1.open("POST", "../check/db-signup.php", true);
            ajax1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            ajax1.send(data);
        }

        
    </script>
</body>

</html>
