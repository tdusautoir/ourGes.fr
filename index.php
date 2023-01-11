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
    <meta name="description" content="ourGes is an extension to myGes. you can easily find your school information using a simple and easy-to-use interface">
    <link rel="stylesheet" href="./public/css/reset.css">
    <link rel="stylesheet" href="./public/css/style.css">
    <link rel="stylesheet" href="./public/css/variables.css">
    <link rel="stylesheet" href="./public/css/keyframes.css">
    <link rel="icon" href="./public/img/favicon.png" />
    <script src="./public/javascript/script.js"></script>
    <script src="./public/javascript/navigate.js" defer></script>
    <title>ourGes 2.0 - myGes, but easier</title>
</head>

<body>
    <?php if (!is_logged()) : ?>
        <form class="login" action="<?= (isset($_GET['surveyToken']) && !empty($_GET['surveyToken'])) ? "./controller/login.php?surveyToken=" . $_GET['surveyToken'] : "./controller/login.php" ?>" method="POST">
            <div class="login__inputs">
                <input class="input" name="username" type="text" placeholder="username" autocomplete="off" maxlength="30">
                <input id="password" class="input" name="password" type="password" placeholder="password" autocomplete="off" maxlength="30">
                <i onclick="password()" class="fa fa-eye-slash" id="eye"></i>
            </div>
            <div class="tag">
                <button type="submit">connect</button>
            </div>
        </form>
    <?php else : ?>
        <div class="logout" id="logout">
            <div class="logout__head">
                <p>Signed in as</p>
                <p><?= $_SESSION['profile']->firstname . " " .  $_SESSION['profile']->name ?></p>
            </div>
            <div class="logout__content">
                <div class="tag">
                    <p><?= $_SESSION['class']->promotion ?></p>
                </div>
                <a href="index.php?action=logout"><i class="fa fa-sign-out"></i></a>
            </div>
        </div>
        <div class="class__background" id="class__modal">
            <div class="class">
                <h2 class="class__title">Challenge Stack semestriel (figma/HTML/CSS)</h2>
                <div class="class__content">
                    <p><i class="fa fa-user-tie" style="font-size: 14px;"></i>Professor : <span class="professor"></span></p>
                    <p>Room : <span class="room"></span></p>
                    <p>Stage : <span class="stage"></span></p>
                    <p>Hour : <span class="date"></span></p>
                    <p>Modality : <span class="modality"></span></p>
                    <p>Campus : <span class="campus"></span></p>
                </div>
                <i class="fa fa-xmark" onclick="class__modal()"></i>
            </div>
        </div>
    <?php endif; ?>
    <!-- ---HEADER--- -->
    <header class="header">
        <div class="header__logo">
            <p>our</p>
            <p>GES</p>
        </div>
        <?php if (!is_logged()) : ?>
            <div class="header__buttons">
                <button onclick="login()">
                    <p>login</p>
                </button>
            </div>
        <?php else : ?>
            <div class="header__buttons" onclick="logout()">
                <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="user image">
                <i class="fa fa-angle-down"></i>
            </div>
        <?php endif; ?>
    </header>
    <?php if (!is_logged()) : ?>
        <main class="hero">
            <div class="hero__text">
                <div class="tag">
                    <p>BUILT BY STUDENTS, FOR STUDENTS</p>
                </div>
                <div class="hero__text__title">
                    <div class="hero__text__title__container">
                        <h1>myGes,</h1>
                    </div>
                    <div class="hero__text__title__container">
                        <h1>but easier.</h1>
                    </div>
                </div>
                <div class="hero__text__description">
                    <p>ourGes is an extension to myGes</p>
                    <p>you can easily find your school information using a simple and easy-to-use interface</p>
                    <p>developed by <a href="https://github.com/achilledavid" target="_blank">achille</a> and <a href="https://github.com/tdusautoir" target="_blank">thibaut</a></p>
                </div>
                <div class="hero__text__buttons">
                    <button onclick="login()">
                        <p>login</p>
                    </button>
                    <a href="https://github.com/tdusautoir/ourGes.fr" target="_blank">
                        <img src="./public/img/github.webp" alt="github logo" draggable="false">
                        <p>see on github</p>
                    </a>
                </div>
            </div>
            <div class="hero__image">
                <img src="./public/img/right-img.webp" alt="hero banner image" draggable="false">
            </div>
        </main>
    <?php else : ?>
        <!-- ---DASHBOARD--- -->
        <div class="dashboard__buttons">
            <a href="./survey/">
                <div class="tag tag--click tag--new">
                    <p><i class="fa fa-chart-pie"></i>Create a survey</p>
                </div>
            </a>
        </div>
        <main class="dashboard">
            <div class="dashboard__left">
                <div class="dashboard__component marks">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-graduation-cap"></i>marks</p>
                        </div>
                        <span>av.</span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['grades']) && !empty($_SESSION['grades'])) : ?>
                            <?php foreach ($_SESSION['grades'] as $grade) : ?>
                                <div class="dashboard__component__content__lign">
                                    <p><?= $grade->course ?></p>
                                    <p><?php if (isset($grade->average)) : echo $grade->average;
                                        endif; ?>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <div class="dashboard__component__content__lign">
                                <p>Empty marks</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dashboard__component news">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-envelope"></i></i>news</p>
                        </div>
                        <div class="dashboard__component__title__arrows">
                            <i onclick="navigateToPrecedentNews()" class="fa fa-angle-left dashboard__component__title__arrows__arrow dashboard__component__title__arrows__arrow--left"></i>
                            <i onclick="navigateToFollowingNews()" class="fa fa-angle-right dashboard__component__title__arrows__arrow dashboard__component__title__arrows__arrow--right"></i>
                        </div>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['news']) && !empty($_SESSION['news'])) : ?>
                            <?php foreach ($_SESSION['news'] as $new) : ?>
                                <?php if (isset($new->ba_id)) : ?>
                                    <div class="dashboard__component__content__banner" style="background-image: url(<?= $new->image ?>);">
                                        <!-- new title-->
                                        <p><?= $new->title ?></p>
                                        <?php if (isset($new->html)) :  ?>
                                            <div>
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
                        <?php else : ?>
                            <p>Empty news</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dashboard__component classes">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-chalkboard-user"></i></i>classes</p>
                        </div>
                        <span>coef.</span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['grades']) && !empty($_SESSION['grades'])) : ?>
                            <?php foreach ($_SESSION['grades'] as $course) : ?>
                                <div class="dashboard__component__content__lign">
                                    <p><?= $course->course . " - " ?><span><?= $course->teacher_civility . ' ' . $course->teacher_first_name . ' ' . $course->teacher_last_name ?></span></p>
                                    <p><?= $course->coef ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="dashboard__component__content__lign">
                                <p>Empty course</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="dashboard__right">
                <div class="dashboard__component planning">
                    <div class="dashboard__component__title">
                        <div class="dashboard__component__date">
                            <div class="tag">
                                <p><i class="fa fa-calendar"></i>planning</p>
                            </div>
                            <?php foreach ($DAYS as $key => $day) : ?>
                                <p class="date"><?= date('l', $day) . " " . date('d/m/y', $day) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <div class="dashboard__component__title__arrows">
                            <i onclick="navigateToPrecedentDay()" class="fa fa-angle-left dashboard__component__title__arrows__arrow dashboard__component__title__arrows__arrow--left"></i>
                            <i onclick="navigateToFollowingDay()" class="fa fa-angle-right dashboard__component__title__arrows__arrow dashboard__component__title__arrows__arrow--right"></i>
                        </div>
                    </div>
                    <div class="dashboard__component__content">
                        <?php foreach ($DAYS as $key => $day) : ?>
                            <div <?php if (date('l', $day) == date('l')) : ?> class="current day" <?php else : ?> class="day" <?php endif ?>>
                                <?php foreach ($_SESSION['agenda'] as $key => $class) : ?>
                                    <?php if (date('l', $class->start_date / 1000) == date('l', $day)) : //if the name of the day is equal --> same day

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
                                        <div class="planning__class <?php if (!empty($className)) {
                                                                        echo $className;
                                                                    } ?>" onclick="get_class_info(<?= $key ?>)">
                                            <p><?= $startDate->format('H:i') ?> - <?= $endDate->format('H:i') ?></p>
                                            <p><?= $class->name ?></p>
                                            <?php if (isset($class->rooms[0])) : ?>
                                                <?php if (isset($class->teacher) && strlen($class->teacher) > 1) : /* $class->teacher is equal to 1 caracter when it's not defined */ ?>
                                                    <p><?= $class->teacher ?> - <?= $class->rooms[0]->name ?></p>
                                                <?php else : ?>
                                                    <p><?= $class->rooms[0]->name ?></p>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="dashboard__component absence">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-message-exclamation"></i>absence</p>
                        </div>
                        <span>date</span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['absences']) && !empty($_SESSION['absences'])) : ?>
                            <?php foreach ($_SESSION['absences'] as $absence) : ?>
                                <div class="dashboard__component__content__lign <?php $absence->justified ? 'justified' : '' ?>">
                                    <p><?= $absence->course_name ?></p>
                                    <p><?= date('d/m/Y', $absence->date / 1000); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="absence__empty">no absences</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    <?php endif ?>
</body>

</html>

<!-- developed by achille david and thibaut dusautoir -->