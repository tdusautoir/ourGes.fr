<?php

require_once './functions.php';
require_once './controller/action.php';

init_php_session();

require_once './agenda.php';
require_once './lang/lang.php';

$lang = $lang[get_user_lang()];

getAverageFromGrades($_SESSION['grades']);

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
    <script src="./public/javascript/functions.js"></script>
    <script src="./public/javascript/navigate.js" defer></script>
    <title><?= $lang['title'] ?></title>
</head>

<body>
    <?php require_once('./components/flash_message.php'); ?>
    <?php if (!is_logged()) : ?>
        <form class="login" action="<?= (isset($_GET['callbackUrl'])) ? "./controller/login.php?callbackUrl=" . $_GET['callbackUrl'] : "./controller/login.php" ?>" method="POST">
            <div class="login__inputs">
                <input class="input" name="username" type="text" placeholder="username" autocomplete="off" maxlength="30">
                <input id="password" class="input" name="password" type="password" placeholder="password" autocomplete="off" maxlength="30">
                <i onclick="password()" class="fa fa-eye-slash" id="eye"></i>
            </div>
            <div class="tag">
                <button type="submit" onclick="loading()"><?= $lang['buttons']['connect'] ?></button>
            </div>
        </form>
    <?php else : ?>
        <div class="logout" id="logout">
            <div class="logout__head">
                <p><?= $lang['sign_as'] ?></p>
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
                <span class="class__date"></span>
                <h2 class="class__title"></h2>
                <div class="class__content">
                    <p><i class="fa fa-user-tie" style="font-size: 14px;"></i><?= $lang['class_modal']['professor'] ?> : <span class="professor"></span></p>
                    <p><?= $lang['class_modal']['room'] ?> : <span class="room"></span></p>
                    <p><?= $lang['class_modal']['stage'] ?> : <span class="stage"></span></p>
                    <p><?= $lang['class_modal']['modality'] ?> : <span class="modality"></span></p>
                    <p><?= $lang['class_modal']['campus'] ?> : <span class="campus"></span></p>
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
                    <p><?= $lang['buttons']['login'] ?></p>
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
                    <p><?= $lang['hero']['text'] ?></p>
                </div>
                <div class="hero__text__title">
                    <div class="hero__text__title__container">
                        <h1><?= $lang['hero']['title'][0] ?></h1>
                    </div>
                    <div class="hero__text__title__container">
                        <h1><?= $lang['hero']['title'][1] ?></h1>
                    </div>
                </div>
                <div class="hero__text__description">
                    <p><?= $lang['hero']['description'][0] ?></p>
                    <p><?= $lang['hero']['description'][1] ?></p>
                    <p><?= $lang['hero']['description']['developed_by'] ?> <a href="https://github.com/achilledavid" target="_blank">achille</a> <?= $lang['hero']['description']['and'] ?> <a href="https://github.com/tdusautoir" target="_blank">thibaut</a></p>
                </div>
                <div class="hero__text__buttons">
                    <button onclick="login()">
                        <p><?= $lang['buttons']['login'] ?></p>
                    </button>
                    <a href="https://github.com/tdusautoir/ourGes.fr" target="_blank">
                        <img src="./public/img/github.webp" alt="github logo" draggable="false">
                        <p><?= $lang['hero']['see_on_github'] ?></p>
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
            <div class="dashboard__buttons__left">
                <select onchange="setSemester(this);">
                    <option value="0" selected disabled><?= $lang['home']['semester'][0] ?></option>
                    <option value="1"><?= $lang['home']['semester'][1] ?></option>
                    <option value="2"><?= $lang['home']['semester'][2] ?></option>
                </select>
            </div>
            <div class="dashboard__buttons__right">
                <a href="./survey/">
                    <div class="tag tag--click tag--new">
                        <p><i class="fa fa-chart-pie"></i><?= $lang['buttons']['survey'] ?></p>
                    </div>
                </a>
            </div>
        </div>
        <main class="dashboard">
            <div class="dashboard__left">
                <div class="dashboard__component marks">
                    <div class="dashboard__component__title">
                        <div class="dashboard__component__title__content">
                            <div class="tag">
                                <p><i class="fa fa-graduation-cap"></i><?= $lang['home']['dashboard']['title']['marks'] ?></p>
                            </div>
                            <span>18,26</span>
                        </div>
                        <span><?= $lang['home']['dashboard']['average'] ?></span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['grades']) && !empty($_SESSION['grades'])) : ?>
                            <?php foreach ($_SESSION['grades'] as $grade) : ?>
                                <div class="dashboard__component__content__lign" data-semester=<?= explode(' ', $grade->trimester_name)[1] ?>>
                                    <p><span class="dashboard__component__content__lign__trimester"><?= 'S' . explode(' ', $grade->trimester_name)[1] ?> - </span><?= $grade->course ?></p>
                                    <?php if (!empty($grade->grades)) : ?>
                                        <p><?php if (isset($grade->ccaverage)) : echo $grade->ccaverage;
                                            endif; ?></p>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach ?>
                        <?php else : ?>
                            <div class="dashboard__component__content__lign">
                                <p><?= $lang['home']['dashboard']['empty']['marks'] ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dashboard__component news">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-envelope"></i></i><?= $lang['home']['dashboard']['title']['news'] ?></p>
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
                            <p><?= $lang['home']['dashboard']['empty']['news'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="dashboard__component classes">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-chalkboard-user"></i></i><?= $lang['home']['dashboard']['title']['classes'] ?></p>
                        </div>
                        <span><?= $lang['home']['dashboard']['coef'] ?></span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['grades']) && !empty($_SESSION['grades'])) : ?>
                            <?php foreach ($_SESSION['grades'] as $course) : ?>
                                <div class="dashboard__component__content__lign" data-semester="<?= explode(' ', $course->trimester_name)[1] ?>">
                                    <p><span class="dashboard__component__content__lign__trimester"><?= 'S' . explode(' ', $course->trimester_name)[1] ?> - </span></span><?= $course->course . " - " ?><span><?= $course->teacher_civility . ' ' . $course->teacher_first_name ?></span></p>
                                    <p><?= $course->coef ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="dashboard__component__content__lign">
                                <p><?= $lang['home']['dashboard']['empty']['courses'] ?></p>
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
                                <p><i class="fa fa-calendar"></i><?= $lang['home']['dashboard']['title']['planning'] ?></p>
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
                        <?php foreach ($DAYS as $key => $day) :
                            $dayEmpty = true; ?>
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

                                        $dayEmpty = false;
                                    ?>
                                        <div class="planning__class<?php if ($class->type == 'Examen') {
                                                                        echo ' examen';
                                                                    } ?><?php if (!empty($className)) {
                                                                            echo ' ' . $className;
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
                                <?php if ($dayEmpty) : ?>
                                    <p class="planning__empty"><?= $lang['home']['dashboard']['empty']['agenda'] ?></p>
                                <?php endif ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="dashboard__component absence">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-message-exclamation"></i><?= $lang['home']['dashboard']['title']['absence'] ?></p>
                        </div>
                        <span><?= $lang['home']['dashboard']['date'] ?></span>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if (isset($_SESSION['absences']) && !empty($_SESSION['absences'])) : ?>
                            <?php foreach ($_SESSION['absences'] as $absence) : ?>
                                <div class="dashboard__component__content__lign<?= $absence->justified ? ' justified' : '' ?>" data-semester="<?= explode(' ', $course->trimester_name)[1] ?>">
                                    <p><?= $absence->course_name ?></p>
                                    <p><?= date('d/m/Y', $absence->date / 1000); ?></p>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p class="absence__empty"><?= $lang['home']['dashboard']['empty']['absence'] ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    <?php endif ?>
</body>

</html>

<!-- developed by achille david and thibaut dusautoir -->