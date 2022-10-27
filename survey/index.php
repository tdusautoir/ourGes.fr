<?php

require_once '../functions.php';
require_once '../controller/action.php';

init_php_session();

require_once '../agenda.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ourGes - Create a survey</title>
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/animations.css">
    <link rel="stylesheet" href="../public/css/var.css">
    <link rel="stylesheet" href="../public/css/global.css">
    <link rel="icon" href="../public/img/favicon.png" />
    <script src="../public/js/script.js"></script>
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/socket.js"></script>
</head>

<body class="m-0a ovf" id="body">

    <?php if (is_logged()) : ?>
        <div class="message flex flex-col" id="message">
            <div class="message__head flex flex-al" onclick="showMessage(); updateScroll()">
                <p class="flex flex-al">#<?= $_SESSION['class']->promotion ?><span>&nbsp;- General Chat</span></p>
                <i class="fa fa-angle-down" id="fa-angle-down-message"></i>
            </div>
            <div class="message__content flex flex-col pd-1">
                <div class="chats-container flex flex-col gap-2" id="chats-container">

                    <!-- <div class="chat flex gap-1">
                        <div class="chat__usr">
                            <img src="./public/img/right-img.webp" alt="">
                        </div>
                        <div class="chat__content flex flex-col">
                            <div class="chat__content__name flex flex-al gap-1">
                                <p>$_SESSION['profile']->firstname</p>
                                <p>19:46</p>
                            </div>
                            <div class="chat__content__text pd-1">
                                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Nemo, quia possimus explicabo ea nihil modi.</p>
                            </div>
                        </div>
                    </div> -->

                </div>
            </div>
            <form id="chat-form">
                <div class="message__write flex gap-1 flex-al">
                    <input type="hidden" value="<?= $_SESSION['profile']->uid ?>" id="id_user">
                    <input type="text" id="chat-message" placeholder="Send a message in #<?= $_SESSION['class']->promotion ?>" autocomplete="off" maxlength="144">
                    <button type="submit" style="background:transparent;"><i class="fa fa-paper-plane"></i></button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if (is_logged()) : ?>
        <div class="test" id="test">
            <nav class="flex flex-al">
                <div class="nav__logo flex flex-js pd-1">
                    <p>our</p>
                    <p onclick="easter()">GES</p>
                </div>
                <div class="nav__menu flex flex-al">
                    <?php if (!is_logged()) : ?>
                        <!-- IS LOGGED -->
                        <button onclick="showForm()">login</button>
                    <?php else : ?>
                        <div class="nav__menu__usr flex">
                            <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="profile" onclick="showSubmenu()">
                            <i class="fa fa-angle-down" id="fa-angle-down"></i>
                        </div>
                    <?php endif; ?>
                </div>
            </nav>
            <div class="nav__submenu pd-1" id="dropdown-menu">
                <div class="nav__submenu__head mb-1">
                    <p>Signed in as</p>
                    <span><?= $_SESSION['profile']->firstname . " " .  $_SESSION['profile']->name ?></span>
                </div>
                <div class="nav__submenu__foot flex">
                    <p class="tag"><?= $_SESSION['class']->promotion ?></p>
                    <a href="index.php?action=logout"><i class="fa fa-sign-out"></i></a>
                </div>
            </div>

            <div class="container" id="container">
                <div class="login m-0a" id="login-form">
                    <form class="flex flex-col flex-al" action="./controller/login.php" method="POST">
                        <div class="login__items gap-1 mb-1 flex flex-al">
                            <input name="username" type="text" placeholder="username" autocomplete="off" maxlength="20">
                            <input id="password" name="password" type="password" placeholder="password" autocomplete="off" maxlength="30">
                            <i onclick="showPwd()" class="fa fa-eye-slash" id="eye"></i>
                        </div>
                        <button type="submit" class="tag">connect</button>
                    </form>
                </div>
                <?php if (!is_logged()) : ?>
                    <div class="content mt-3 flex">
                        <div class="hero flex flex-col flex-js">
                            <span class="tag mb-2">Built by students, for students</span>
                            <div class="hero__title">
                                <p>myGes,</p>
                            </div>
                            <div class="hero__title mb-2">
                                <p class="snd">but easier.</p>
                            </div>
                            <div class="hero__headline">
                                <p>
                                    ourGes is an extension to myGes
                                </p>
                                <p class="mb-1">
                                    you can easily find your school information using a simple and easy-to-use interface
                                </p>
                                <p class="mb-2">
                                    developed by <a href="https://github.com/achilledavid" target="blank">achille</a> and <a href="https://github.com/tdusautoir" target="blank">thibaut</a>
                                </p>
                                <div class="hero__buttons flex">
                                    <button onclick="showForm()">
                                        <p>login</p>
                                    </button>
                                    <a class="flex flex-js flex-al" href="https://github.com/tdusautoir/ourGes" target="blank">
                                        <img src="../public/img/github.webp" alt="">
                                        <p>see on github</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="img flex">
                            <img src="../public/img/right-img.webp" alt="" draggable="false">
                        </div>
                    </div>
                <?php else : ?>
                    <div class="container container-survey">
                        <a href="../">
                            <p>retour</p>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <img src="https://www.section.io/engineering-education/authors/michael-barasa/avatar.png" class="michael" id="michael" alt="">
        </div>

    <?php else : ?>
        <div class="container container-survey">
            <a href="../">
                <p>retour</p>
            </a>
        </div>
    <?php endif; ?>

    <?php require '../components/flash_message.php'; ?>
</body>

</html>

<!-- developed by achille david and thibaut dusautoir -->