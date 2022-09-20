<?php

require_once './functions.php';
init_php_session();

if (isset($_GET['action'])) {
    if ($_GET['action'] == 'logout') {
        clean_php_session();
        header("location: ./");
    }
}

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
                <!-- <a>test</a>
                <a>test</a>
                <a>test</a> -->
                <!-- IS LOGGED -->
                <?php if (!is_logged()) : ?>
                    <button onclick="showForm()">login</button>
                <?php else : ?>
                    <!-- NOT LOGGED -->
                    <div class="usr-img">
                        <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="profile" onclick="showSubmenu()">
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
                        <p>myGes,</p>
                    </div>
                    <div class="title-effect mb-1">
                        <p class="snd">but better.</p>
                    </div>
                    <div class="headline">
                        <p>
                            ourGes is an extension to myGes
                        </p>
                        <p class="mb-2">
                            you can easily find your school information using a simple and easy-to-use interface
                        </p>
                        <p class="mb-1">
                            developed by <a href="https://github.com/achilledavid" target="blank">achille</a> and <a href="https://github.com/tdusautoir" target="blank">thibaut</a>
                        </p>
                        <div class="headline-buttons">
                            <button onclick="showForm()">
                                <p>login</p>
                            </button>
                            </a>
                            <a href="https://github.com/tdusautoir/ourGes" target="blank">
                                <img src="./img/github.png" alt="">
                                <p>see on github</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="right-img">
                    <img src="./public/img/right-img.png" alt="" draggable="false">
                </div>
            </div>
        <?php else : ?>
            <div class="content">
                <div class="dashboard">
                    <div class="dsb-left">
                        <div class="notes case">
                            <div class="dashboard-head mb-2">
                                <h4 class="tag">News</h4>
                                <div class="dashboard-legend">
                                    <span>Marks</span>
                                    <span>Average</span>
                                </div>
                            </div>
                            <?php foreach ($_SESSION['grades'] as $grade) : ?>
                                <div class="grade">
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
                        <div class="news case">
                            <div class="dashboard-head mb-2">
                                <h4 class="tag">News</h4>
                                <div class="arrows-dashboard-head">
                                    <i class="fa fa-angle-down"></i>
                                    <i class="fa fa-angle-down"></i>
                                </div>
                            </div>
                            <?php foreach ($_SESSION['news'] as $new) : ?>
                                <?php if (isset($new->ba_id)) : ?>
                                    <div class="new-banner" style="background-image: url(<?= $new->image ?>);">
                                        <!-- new title-->
                                        <p class="title"><?= $new->title ?></p>
                                        <div class="description">
                                            <?php if (isset($new->url)) : ?>
                                                <!-- new video-->
                                                <iframe width="200" height="110" src="<?php echo str_replace('watch?v=', 'embed/', $new->url); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            <?php endif; ?>
                                            <!-- new description -->
                                            <?php if (isset($new->html)) :  ?>
                                                <?= $new->html ?>
                                            <?php else : ?>
                                                <p>Aucune description</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <div class="courses case">
                            <div class="title mb-2">
                                <h4 class="tag">classes</h4>
                                <span class="coef">coef</span>
                            </div>
                            <div class="course-list">
                                <?php foreach ($_SESSION['grades'] as $course) : ?>
                                    <div class="course">
                                        <!-- name of the course - professor -->
                                        <p><?= $course->course . " - " ?><span class='teacher'><?= $course->teacher_civility . ' ' . $course->teacher_first_name . ' ' . $course->teacher_last_name ?></span></p>
                                        <!-- coef -->
                                        <span class="coef"><?= $course->coef ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="dsb-right">
                        <div class="planning case">
                            <div class="dashboard-head mb-2">
                                <h4 class="tag">Planning</h4>
                                <div class="arrows-dashboard-head">
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

</html>

<!-- devloped by achille david and thibaut dusautoir -->