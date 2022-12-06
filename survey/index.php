<?php

require_once '../functions.php';
require_once '../controller/action.php';
require_once '../models/Survey.php';

init_php_session();

if (!is_logged()) {
    header("location: ../");
}

if (!is_logged() && isset($_GET['token']) && !empty($_GET['token'])) {
    header("location: ../?surveyToken=" . $_GET['token']);
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
    <link rel="stylesheet" href="../public/css/responsive.css">
    <link rel="icon" href="../public/img/favicon.png" />
    <script src="../public/js/jquery-3.6.0.min.js"></script>
    <script src="../public/js/script.js" defer></script>
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
            <span>
                <?= $_SESSION['profile']->firstname . " " .  $_SESSION['profile']->name ?>
            </span>
        </div>
        <div class="nav__submenu__foot flex">
            <p class="tag">
                <?= $_SESSION['class']->promotion ?>
            </p>
            <a href="index.php?action=logout"><i class="fa fa-sign-out"></i></a>
        </div>
    </div>
    <div class="container container-survey" id="container">
        <div class="content m-0a">
            <div class="db-btn mb-2 flex gap-2">
                <a href="javascript:delay('../')" onclick="transition()">
                    <p class="db-btn__itm gap-1 flex flex-al tag"> Back </p>
                </a>
            </div>
            <div class="dashboard flex">
                <div class="dashboard__row flex">
                    <div class="dashboard__card pd-1">
                        <div class="dashboard__card__head flex flex-al mb-2">
                            <div class="dashboard__card__head__title flex flex-al gap-1">
                                <h4 class="tag">Create a survey</h4>
                            </div>
                        </div>
                        <form action="../controller/survey.php" class="survey__create" method="POST">
                            <div class="survey__form__container">
                                <input type="text" name="name" autocomplete="off" maxlength="50" placeholder="Name">
                                <input type="text" name="description" autocomplete="off" maxlength="100" placeholder="Description (optional)">

                                <input type="hidden" id="nb-choice" name="nb-choice" value="2">
                                <select name="type">
                                    <option value="1" selected disabled>Type</option>
                                    <option value="1">Simple choice</option>
                                    <option value="2">Multiple choices</option>
                                </select>
                                <div id="choices__container" class="choices__container">
                                    <div class="survey-choices-input">
                                        <input type="text" name="choice-1" autocomplete="off" maxlength="50" placeholder="Choice 1">
                                    </div>
                                    <div class="survey-choices-input">
                                        <input type="text" name="choice-2" autocomplete="off" maxlength="50" placeholder="Choice 2">
                                    </div>
                                </div>
                                <div class="addOption">
                                    <div class="addOption__content tag" onclick="addOption()">
                                        Add a choice<i class="fa fa-plus"></i>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="tag">Create</button>
                        </form>
                    </div>
                </div>
                <div class="dashboard__col flex flex-col">
                    <div class="dashboard__card pd-1">
                        <?php if (isset($_GET['token'])) : ?>
                            <div class="dashboard__card__head flex flex-al mb-1">
                                <h4 class="tag">Survey</h4>
                            </div>
                            <div class="survey__content">
                                <h1 class="mb-1"><?= $survey_data['name'] ?></h1>
                                <?php if (isset($survey_data['description']) && !empty($survey_data['description'])) : ?>
                                    <p class="mb-2"><?= $survey_data['description'] ?></p>
                                <?php endif; ?>
                                <form id="form_survey">
                                    <div class="survey__choices" id="survey__choices">
                                        <?php foreach ($survey_data['choices'] as $choice) : ?>
                                            <div class="survey__choice" id="survey__choice">
                                                <?php if ($survey_data['type'] == 1) : ?>
                                                    <button type="button" value='<?= $choice['id'] ?>' onclick="submitChoice(this)">
                                                        <p><?= $choice['choice'] ?></p>
                                                    </button>
                                                <?php else : ?>
                                                    <input name='choices' type='checkbox' value='<?= $choice['id'] ?>' name=<?= strtolower($choice['choice']) ?>>
                                                    <label for=<?= strtolower($choice['choice']) ?>><?= $choice['choice'] ?></label>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                        <?php if (!($survey_data['type'] == 1)) : ?>
                                            <button type="submit">Valider</button>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                        <?php else : ?>
                            <div class="dashboard__card__head flex flex-al mb-1">
                                <h4 class="tag">Survey</h4>
                            </div>
                            <div class="survey__choice">
                                <p>aucun sondage</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="dashboard__card pd-1">
                        <div class="dashboard__card__head flex flex-al mb-1">
                            <h4 class="tag">Recent</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <?php require '../components/flash_message.php'; ?>
</body>

</html>
<!-- developed by achille david and thibaut dusautoir -->