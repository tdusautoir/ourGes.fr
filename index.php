<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurGes</title>
    <script src="./public/js/script.js"></script>
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/animations.css">
    <link rel="stylesheet" href="./public/css/var.css">
    <link rel="stylesheet" href="./public/css/global.css">
</head>

<body>
    <div class="container">
        <nav>
            <div class="nav-logo">
                <p>our</p>
                <p>GES</p>
            </div>
            <div class="nav-menu">
                <a>test</a>
                <a>test</a>
                <a>test</a>
                <!-- IS LOGGED -->
                <!-- <img src="./img/user.png" alt=""> -->
                <button onclick="showForm()">login</button>
            </div>
        </nav>
        <div class="content">
            <div class="login-form" id="login-form">
                <form action="./controller/login.php" method="POST">
                    <input name="login" type="text">
                    <input name="password" type="password">
                    <button type="submit">Se connecter</button>
                </form>
            </div>
            <div class="left-title">
                <div class="title-effect">
                    <p>a better</p>
                </div>
                <div class="title-effect">
                    <p class="snd">myGes.</p>
                </div>
            </div>
            <div class="right-img">
                <img src="./img/right-img.png" alt="" draggable="false">
            </div>
        </div>
    </div>
</body>

</html>