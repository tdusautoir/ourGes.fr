<?php

require_once './functions.php';
init_php_session();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        clean_php_session();
        header("location: ./");
    }
}

$pageNews = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ourGes - myGes, but better</title>
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/animations.css">
    <link rel="stylesheet" href="./public/css/var.css">
    <link rel="stylesheet" href="./public/css/global.css">
    <link rel="icon" href="./img/favicon.png" />
    <script src="./public/js/script.js"></script>
</head>

<body class="m-0a ovf">
    <div class="container mb-5">
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
        <nav class="flex flex-al">
            <div class="nav__logo flex flex-js pd-1">
                <p>our</p>
                <p>GES</p>
            </div>
            <div class="nav__menu flex flex-al">
                <?php if (!is_logged()) : ?>
                    <button onclick="showForm()">login</button>
                <?php else : ?>
                    <div class="nav__menu__usr flex">
                        <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="profile" onclick="showSubmenu()">
                        <i class="fa fa-angle-down" id="fa-angle-down"></i>
                    </div>
                <?php endif; ?>
            </div>
        </nav>
        <div class="nav__submenu" id="dropdown-menu">
            <div class="nav__submenu__head mb-1">
                <p>Signed in as</p>
                <span><?= $_SESSION['profile']->firstname . " " .  $_SESSION['profile']->name ?></span>
            </div>
            <div class="nav__submenu__foot flex">
                <p class="tag"><?= $_SESSION['class']->promotion ?></p>
                <a href="index.php?action=logout"><i class="fa fa-sign-out"></i></a>
            </div>
        </div>
        <?php if (!is_logged()) : ?>
            <div class="content flex">
                <div class="hero flex flex-col flex-js">
                    <span class="tag mb-2">Built by students, for students</span>
                    <div class="hero__title">
                        <p>myGes,</p>
                    </div>
                    <div class="hero__title mb-2">
                        <p class="snd">but better.</p>
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
                                <img src="./img/github.png" alt="">
                                <p>see on github</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="img flex">
                    <img src="./public/img/right-img.png" alt="" draggable="false">
                </div>
            </div>
        <?php else : ?>
            <div class="content m-0a">
                <div class="dashboard flex">
                    <div class="dsb-left flex flex-col">
                        <div class="notes case">
                            <div class="dashboard-head flex flex-al mb-2">
                                <h4 class="tag">Marks</h4>
                                <div class="dashboard-legend">
                                    <span>Marks</span>
                                    <span>Average</span>
                                </div>
                            </div>
                            <div class="course-list">
                                <?php foreach ($_SESSION['grades'] as $grade) : ?>
                                    <div class="grade flex">
                                        <p class="course-name"><?= $grade->course ?></p>
                                        <div>
                                            <span class="marks">
                                                <?php if (isset($grade->grades)) :
                                                    foreach ($grade->grades as $key => $mark) :
                                                        if (end($grade->grades) == $mark) :
                                                            echo $mark;
                                                        else :
                                                            echo $mark . ",";
                                                        endif;
                                                    endforeach;
                                                endif; ?>
                                            </span>
                                            <span class="average">
                                                <?php if (isset($grade->average)) :
                                                    echo $grade->average;
                                                endif; ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                        <div class="news case">
                            <div class="dashboard-head flex mb-1">
                                <h4 class="tag">News</h4>
                                <div class="arrows-dashboard-head gap-1 flex">
                                    <i onclick="navigateToPrecedent()" class="fa fa-angle-down"></i>
                                    <i onclick="navigateToFollowing()" class="fa fa-angle-down"></i>
                                </div>
                            </div>
                            <?php foreach ($_SESSION['news'] as $new) :
                                $pageNews++; ?>
                                <?php if (isset($new->ba_id)) : ?>
                                    <div class="new-banner" id=new-<?= $pageNews ?> style="background-image: url(<?= $new->image ?>);">
                                        <!-- new title-->
                                        <p class="title flex mb-1"><?= $new->title ?></p>
                                        <div class="description">
                                            <?php if (isset($new->url)) : ?>
                                                <!-- new video-->
                                                <iframe width="200" height="110" src="<?php echo str_replace('watch?v=', 'embed/', $new->url); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <?php endif; ?>
                                            <!-- new description -->
                                            <?php if (isset($new->html)) :  ?>
                                                <?= $new->html ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="courses case">
                            <div class="title flex mb-1">
                                <h4 class="tag">classes</h4>
                                <span class="coef">coef</span>
                            </div>
                            <div class="course-list">
                                <?php foreach ($_SESSION['grades'] as $course) : ?>
                                    <div class="course flex">
                                        <!-- name of the course - professor -->
                                        <p><?= $course->course . " - " ?><span class='teacher'><?= $course->teacher_civility . ' ' . $course->teacher_first_name . ' ' . $course->teacher_last_name ?></span></p>
                                        <!-- coef -->
                                        <span class="coef"><?= $course->coef ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="dsb-right flex">
                        <div class="planning case">
                            <div class="dashboard-head flex flex-al mb-1">
                                <h4 class="tag">Planning</h4>
                                <div class="arrows-dashboard-head gap-1 flex">
                                    <i class="fa fa-angle-down"></i>
                                    <i class="fa fa-angle-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php require './components/flash_message.php'; ?>
</body>
<script src="./public/js/navigate.js"></script>

</html>

<!-- devloped by achille david and thibaut dusautoir -->