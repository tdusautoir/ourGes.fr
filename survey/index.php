<?php

require_once '../functions.php';
require_once '../controller/action.php';
require_once '../models/Survey.php';

init_php_session();

if (!is_logged()) {
    header("location: ../");
}

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $survey = new Survey;
    $survey->setToken($_GET['token']);
    $survey_data = $survey->get_survey();

    if (!$survey_data) {
        create_flash_message('token_error', 'This token id is invalid', FLASH_ERROR);
        header("location: ../survey/");
        return;
    }

    $survey_data['choices'] = $survey->get_choices();
}

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
    <link rel="stylesheet" href="../public/css/survey.css">
    <link rel="icon" href="../public/img/favicon.png" />
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js"></script>
    <script src="../public/js/survey.js"></script>
</head>

<body class="m-0a ovf" id="body">
    <nav class="flex flex-al">
        <div class="nav__logo flex flex-js pd-1">
            <p>our</p>
            <p onclick="easter()">GES</p>
        </div>
        <div class="nav__menu flex flex-al">
            <div class="nav__menu__usr flex">
                <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="profile" onclick="showSubmenu()">
                <i class="fa fa-angle-down" id="fa-angle-down"></i>
            </div>
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
    <div class="container container-survey" id="container">
        <div class="content m-0a">
            <div class="survey">
                <?php if (!isset($_GET['token']) || empty($_GET['token'])) : ?>
                    <div class="survey__form">
                        <h1>Create a survey</h1>
                        <form action="../controller/survey.php" method="POST">
                            <div class="survey__form__name">
                                <input type="text" name="name" class="survey-name" autocomplete="off" maxlength="50" placeholder="Name">
                                <p onclick="addDesc()" id="survey__desc__name" class="survey__desc__name"><i class="fa fa-plus"></i>Add a description</p>
                            </div>
                            <div class="survey-desc-container" id="survey__desc">
                                <input type="text" name="description" class="survey__desc" autocomplete="off" maxlength="100" placeholder="Description (optional)">
                                <p onclick="addDesc()" id="survey__desc__name" class="desc__mask"><i class="fa fa-minus"></i>Mask description</p>
                            </div>
                            <select name="type">
                                <option value="1" selected disabled>Type</option>
                                <option value="1">Choix simple</option>
                                <option value="2">Plusieurs choix</option>
                            </select>
                            <input type="hidden" name="nb-choice" value="1">
                            <input type="text" name="choice-1" autocomplete="off" maxlength="50" placeholder="Choice">
                            <div class="create-container">
                                <!-- <input type="checkbox"> -->
                                <button type="submit" class="tag">Create</button>
                            </div>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="survey__title">
                        <h1><?= $survey_data['name'] ?></h1>
                    </div>
                    <div class="survey__content">
                        <p>Description : <?= $survey_data['description'] ?></p>
                        <div class="survey__form">
                            <form action="../controller/survey.php" method="POST">
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <a href="../">
                <p>retour</p>
            </a>
        </div>
    </div>

    <?php require '../components/flash_message.php'; ?>
</body>

</html>

<!-- developed by achille david and thibaut dusautoir -->