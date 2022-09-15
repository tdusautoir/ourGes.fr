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
                <div class="login-input">
                    <input name="username" type="text" placeholder="username" autocomplete="off" maxlength="20">
                    <input name="password" type="password" placeholder="password" autocomplete="off" maxlength="30">
                </div>
                <button type="submit" class="tag">connect</button>
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
                    <div class="usr-img" onclick="showSubmenu()">
                        <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="photo de profil">
                        <i class="fa fa-angle-down" id="fa-angle-down"></i>
                    </div>
                <?php endif; ?>
                <i class="fa fa-bars"></i>
            </div>
        </nav>
        <div class="dropdown-menu" id="dropdown-menu">
            <div class="dropdown-menu-1 mb-2">
                <p>Signed in as</p>
                <span><?= $_SESSION['profile']->firstname . " " .  $_SESSION['profile']->name ?></span>
            </div>
            <div class="dropdown-menu-2">
                <p class="tag"><?= $_SESSION['class']->promotion ?></p>
                <a href="index.php?action=logout"><i class="fa fa-sign-out"></i></a>
            </div>
        </div>
        <?php if (!is_logged()) : ?>
            <div class="content flex">
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
        <?php else : ?>
            <div class="content">
                <span class="tag mb-1"><?= str_replace('3', '3 ', $_SESSION['class']->promotion) . " - " . $_SESSION['class']->year; ?></span>
            </div>
        <?php endif; ?>
    </div>
</body>

<script src="./public/js/script.js"></script>

<script src="https://kit.fontawesome.com/ee66252351.js" crossorigin="anonymous"></script>

</html>