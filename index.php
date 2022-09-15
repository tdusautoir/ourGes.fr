<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OurGes</title>
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/animations.css">
    <link rel="stylesheet" href="./public/css/var.css">
    <link rel="stylesheet" href="./public/css/global.css">
</head>

<body>
    <div class="container">
        <div class="login-form" id="login-form">
            <form action="./controller/login.php" method="POST">
                <input name="login" type="text">
                <input name="password" type="password">
                <button type="submit">connect</button>
            </form>
        </div>
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
                <!-- NOT LOGGED -->
                <button onclick="showForm()">login</button>
                <i class="fa fa-bars"></i>
            </div>
        </nav>
        <div class="content">
            <div class="left-title">
                <span class="tag mb-1">Built by students, for students</span>
                <div class="title-effect">
                    <p>a better</p>
                </div>
                <div class="title-effect mb-1">
                    <p class="snd">myGes.</p>
                </div>
                <div class="headline">
                    <p class="mb-1">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quo tempore accusantium ipsa temporibus doloribus aliquid repudiandae cupiditate labore veritatis neque omnis amet ab, alias sunt commodi incidunt blanditiis quidem tenetur voluptas fugiat officiis nulla eveniet vel excepturi? Nulla quam quaerat necessitatibus, aliquam maiores, earum at ipsam animi, est a repudiandae.</p>
                    <div class="headline-buttons">
                        <button onclick="showForm()">
                            <p>login</p>
                        </button>
                        </a>
                        <a href="https://myges.fr/#/" target="blank">
                            <p>myges</p>
                        </a>
                    </div>
                </div>
            </div>
            <div class="right-img">
                <img src="./img/right-img.png" alt="" draggable="false">
            </div>
        </div>
    </div>
</body>

<script src="./public/js/script.js"></script>

<script src="https://kit.fontawesome.com/ee66252351.js" crossorigin="anonymous"></script>

</html>