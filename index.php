<?php

require_once './functions.php';
init_php_session();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        clean_php_session();
    }
}

?>

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
                <input name="username" type="text">
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
                <?php if (!is_logged()) : ?>
                    <button onclick="showForm()">login</button>
                <?php else : ?>
                    <!-- NOT LOGGED -->
                    <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="photo de profil" onclick="showSubmenu()">
                <?php endif; ?>
                <i class="fa fa-bars"></i>
            </div>
        </nav>
        <div class="dropdown-menu" id="dropdown-menu">
            <p>Signed in as</p>
            <span>Achille David</span>
            <a href="index.php?action=logout">disconnet</a>
        </div>
        <div class="content">
            <?php if (!is_logged()) : ?>
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
            <?php else : ?>
                <span class="tag mb-1"><?= $_SESSION['class']->name ?></span>
            <?php endif; ?>
        </div>
    </div>
</body>

<script src="./public/js/script.js"></script>

<script src="https://kit.fontawesome.com/ee66252351.js" crossorigin="anonymous"></script>

</html>