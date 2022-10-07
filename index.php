<?php

require_once './functions.php';
require_once './controller/action.php';

init_php_session();

require_once './agenda.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ourGes - myGes, but easier</title>
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/animations.css">
    <link rel="stylesheet" href="./public/css/var.css">
    <link rel="stylesheet" href="./public/css/global.css">
    <link rel="icon" href="./public/img/favicon.png" />
    <script src="./public/js/script.js"></script>
    <script src="./public/js/jquery-3.6.0.min.js"></script>
    <script src="./public/js/socket.js"></script>
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
                                <img src="./public/img/github.webp" alt="">
                                <p>see on github</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="img flex">
                    <img src="./public/img/right-img.webp" alt="" draggable="false">
                </div>
            </div>
        <?php else : ?>
            <div class="content m-0a">
                <div class="dashboard flex">
                    <div class="dashboard__col flex flex-col">

                        <div class="dashboard__card pd-1">
                            <div class="dashboard__card__head flex flex-al mb-1">
                                <div class="dashboard__card__head__title flex flex-al gap-1">
                                    <h4 class="tag">Marks</h4>
                                    <!-- <p class="global-average">15.35</p> -->
                                </div>
                                <div class="dashboard__card__legend dashboard__card__tab flex gap-1">
                                    <span>Av.</span>
                                </div>
                            </div>
                            <div class="course-list">
                                <?php foreach ($_SESSION['grades'] as $grade) : ?>
                                    <div class="course-list__grade flex">
                                        <p class="course-list__name"><?= $grade->course ?></p>
                                        <div class="dashboard__card__tab flex gap-1">
                                            <!-- <p class="course-list__marks">
                                                <?php if (isset($grade->grades)) :
                                                    foreach ($grade->grades as $key => $mark) :
                                                        if (end($grade->grades) == $mark) :
                                                            echo $mark;
                                                        else :
                                                            echo $mark . ",";
                                                        endif;
                                                    endforeach;
                                                endif; ?>
                                            </p> -->
                                            <p class="course-list__average ml-tab mr-tab">
                                                <?php if (isset($grade->average)) :
                                                    echo $grade->average;
                                                endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="dashboard__card pd-1">
                            <div class="dashboard__card__head flex flex-al mb-1">
                                <h4 class="tag">News</h4>
                                <div class="dashboard__head__arrows gap-1 flex">
                                    <i onclick="navigateToPrecedent()" class="fa fa-angle-down"></i>
                                    <i onclick="navigateToFollowing()" class="fa fa-angle-down"></i>
                                </div>
                            </div>
                            <?php foreach ($_SESSION['news'] as $new) : ?>
                                <?php if (isset($new->ba_id)) : ?>
                                    <div class="news__banner pd-1" style="background-image: url(<?= $new->image ?>);">
                                        <!-- new title-->
                                        <p class="news__banner__title flex mb-1"><?= $new->title ?></p>
                                        <?php if (isset($new->html)) :  ?>
                                            <div class="news__banner__desc pd-1">
                                                <?php if (isset($new->url)) : ?>
                                                    <!-- new video-->
                                                    <iframe width="200" height="110" src="<?php echo str_replace('watch?v=', 'embed/', $new->url); ?>" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                <?php endif; ?>
                                                <!-- new description -->
                                                <?= $new->html ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="dashboard__card pd-1">
                            <div class="dashboard__card__head flex flex-al mb-1">
                                <h4 class="tag">Classes</h4>
                                <span class="course-list__course__coef">coef</span>
                            </div>
                            <div class="course-list">
                                <?php foreach ($_SESSION['grades'] as $course) : ?>
                                    <div class="course-list__course flex">
                                        <!-- name of the course - professor -->
                                        <p><?= $course->course . " - " ?><span class='course-list__course__teacher'><?= $course->teacher_civility . ' ' . $course->teacher_first_name . ' ' . $course->teacher_last_name ?></span></p>
                                        <!-- coef -->
                                        <span class="course-list__course__coef"><?= $course->coef ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard__row flex">

                        <div class="dashboard__card pd-1">
                            <div class="dashboard__card__head flex flex-al mb-2">
                                <div class="dashboard__card__head__title flex flex-al gap-1">
                                    <h4 class="tag">Planning</h4>
                                    <?php foreach ($DAYS as $key => $day) : ?>
                                        <div class="date-container flex">
                                            <p class="date"><?= date('l', $day) ?></p>
                                            <p class="date"><?= date('d/m/y', $day) ?></p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div class="dashboard__head__arrows gap-1 flex">
                                    <i onclick="navigateToPrecedentDay()" id="pl-lst" class="fa fa-angle-down"></i>
                                    <i onclick="navigateToFollowingDay()" id="pl-nxt" class="fa fa-angle-down"></i>
                                </div>
                            </div>
                            <div class="planning__content">
                                <?php foreach ($DAYS as $key => $day) : ?>
                                    <div <?php if (date('l', $day) == date('l')) : ?> class="current day" <?php else : ?> class="day" <?php endif ?>>
                                        <?php foreach ($_SESSION['agenda'] as $key => $class) : ?>
                                            <?php if (date('l', $class->start_date / 1000) == date('l', $day)) : //if the name of the day (ex : 'Monday') is equal --> same day

                                                //calcul the class time
                                                $startDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$key]->start_date / 1000));
                                                $endDate = new DateTime(date('Y-m-d H:i:s', $_SESSION['agenda'][$key]->end_date / 1000));

                                                //calcul the class time
                                                $interval = date_diff($startDate, $endDate);
                                                if ($interval->format('%h') >= 4) {
                                                    $className = 'class--long-4';
                                                } elseif ($interval->format('%h') >= 3) {
                                                    $className = 'class--long';
                                                } else {
                                                    $className = "";
                                                }
                                            ?>
                                                <div class="class <?php if (!empty($className)) {
                                                                        echo $className;
                                                                    } ?>" onclick="showClassModal(); getClassInfo(<?= $key ?>);">
                                                    <p class="class__hour mb-1"><?= $startDate->format('H:i') ?> - <?= $endDate->format('H:i') ?></p>
                                                    <div class="class__details ml-1">
                                                        <p><?= $class->name ?></p>
                                                        <?php if (isset($class->comment)) : ?>
                                                            <p><?= $class->comment ?></p>
                                                        <?php endif; ?>
                                                        <?php if (isset($class->rooms[0])) : ?>
                                                            <?php if (isset($class->teacher) && strlen($class->teacher) > 1) : /* $class->teacher is equal to 1 caracter when it's not defined */ ?>
                                                                <p><?= $class->teacher ?> - <?= $class->rooms[0]->name ?></p>
                                                            <?php else : ?>
                                                                <p><?= $class->rooms[0]->name ?></p>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src=" ./public/js/navigate.js"></script>
            <script>
                init_socket('<?= $_SESSION['class']->promotion; ?>');
            </script>
        <?php endif; ?>
    </div>

    <div class="class__modal__bg" id="class__modal__bg">
        <div class="class__modal" id="class__modal">
            <div class="class__modal__title">
                <i class="fa fa-xmark" onclick="hideClassModal()"></i>
                <p>09:30 - 13:00</p>
                <h1>Langage C - Avancé</h1>
            </div>
            <div class="class__modal__content">
                <p>Professor : <span>M. Khalid GABER</span></p>
                <p>Room : <span>207</span></p>
                <p>Stage : <span>2ème étage</span></p>
                <p>Modality : <span>Présentiel</span></p>
                <p>Campus : <span>LILLE-SAFED</span></p>
            </div>
        </div>
    </div>

    <!-- <img src="https://www.section.io/engineering-education/authors/michael-barasa/avatar.png" class="michael" id="michael" alt=""> -->
    <?php require './components/flash_message.php'; ?>
</body>

</html>

<!-- developed by achille david and thibaut dusautoir -->