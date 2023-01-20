<?php

require_once '../functions.php';
require_once '../controller/action.php';
require_once '../models/Survey.php';
require_once '../components/validation.php';

init_php_session();

if (isset($_GET['token']) && !empty($_GET['token'])) {
    $survey = new Survey;
    $survey->setToken($_GET['token']);
    $survey->setUser($_SESSION['profile']->uid);
    $survey_data = $survey->get_survey();

    if (!$survey_data) {
        create_flash_message('token_error', 'This token id is invalid', FLASH_ERROR);
        header("location: ../survey/");
        return;
    }

    $survey_data['choices'] = $survey->get_choices();
    $survey_data['responses'] = $survey->get_responses();
    $survey_data['current_user_response'] = $survey->get_response_from_user();
    $survey_data['users_infos'] = $survey->get_users_info();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ourGes is an extension to myGes. you can easily find your school information using a simple and easy-to-use interface">
    <link rel="stylesheet" href="../public/css/reset.css">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/variables.css">
    <link rel="stylesheet" href="../public/css/keyframes.css">
    <link rel="icon" href="../public/img/favicon.png" />
    <script src="../public/javascript/script.js"></script>
    <script src="../public/javascript/survey.js" defer></script>
    <title>ourGes - Create a survey</title>
</head>

<body>
    <!-- ---MODALS--- -->
    <?php require_once('../components/flash_message.php'); ?>
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
    <!-- ---HEADER--- -->
    <header class="header">
        <div class="header__logo">
            <p>our</p>
            <p>GES</p>
        </div>
        <div class="header__buttons" onclick="logout()">
            <img src="<?= $_SESSION['profile']->_links->photo->href ?>" alt="user image">
            <i class="fa fa-angle-down"></i>
        </div>
    </header>
    <div class="dashboard__buttons">
        <p> </p>
        <a href="../">
            <div class="tag tag--click">
                <p><i class="fa fa-arrow-left"></i>Back</p>
            </div>
        </a>
    </div>
    <main class="dashboard survey">
        <?php if (isset($survey_data) && !empty($survey_data)) : ?>
            <div class="dashboard__left">
                <div class="dashboard__component answer" id="answer">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-square-poll-vertical"></i>Answer</p>
                        </div>
                    </div>
                    <form method="POST" id="form_survey">
                        <input type="hidden" name="token" value="<?= $_GET['token'] ?>">
                        <div class="dashboard__component__content">
                            <div class="answer__head">
                                <div class="dashboard__component__content__lign answer__head__title">
                                    <p><?= $survey_data['name'] ?></p>
                                </div>
                                <?php if (isset($survey_data['description']) && !empty($survey_data['description'])) : ?>
                                    <div class="dashboard__component__content__lign answer__head__description">
                                        <p><?= $survey_data['description'] ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php foreach ($survey_data['choices'] as $choice) : ?>
                                <?php if ($survey_data['type'] == 1) : ?>
                                    <label for="<?= 'choice_' . $choice['id']; ?>" class="dashboard__component__content__lign answer__option">
                                        <p><?= $choice['choice'] ?></p>
                                        <input name="choice" id="<?= 'choice_' . $choice['id']; ?>" value='<?= $choice['id'] ?>' type="radio">
                                    </label>
                                <?php else : ?>
                                    <label for="<?= 'choice_' . $choice['id']; ?>" class="dashboard__component__content__lign answer__option">
                                        <p><?= $choice['choice'] ?></p>
                                        <input name="choice" id="<?= 'choice_' . $choice['id']; ?>" value='<?= $choice['id'] ?>' type="checkbox">
                                    </label>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($survey_data['current_user_response']) > 0) : ?>
                            <div class="survey__answered">
                                <h1>You have already answered this survey</h1>
                                <p>see the answers down there <i class="fa fa-angle-down"></i></p>
                            </div>
                        <?php endif; ?>
                        <div class="answer__buttons">
                            <button id="send_response_btn" type="submit" class="tag tag--click tag--check">
                                <p>submit</p>
                                <i class="fa fa-circle-check"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="dashboard__component results" id="results">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p>Results</p>
                        </div>
                    </div>
                    <div class="dashboard__component__content">
                        <?php if ($survey_data['nb_responses']) :
                            foreach ($survey_data['responses'] as $response) : ?>
                                <div class="results__bar__container">
                                    <p><?= $response['choice'] ?></p>
                                    <div class="dashboard__component__content__lign results__bar" id="<?= 'result' . $response['choice_id'] ?>">
                                        <div class="results__bar__fill" style="width: <?= $response['choice_percentage'] ?>%"></div>
                                    </div>
                                    <div class="results__bar__container__images">
                                        <?php foreach ($survey_data['users_infos'] as $user) :
                                            if ($user['choice_id'] == $response['choice_id']) : ?>
                                                <img src="<?= isset($user['user_img']) ? $user['user_img'] : 'default.png' ?>" title="<?= $user['user_name'] ?>">
                                        <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p>No results</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <div class="dashboard__left">
                <div class="dashboard__component">
                    <div class="dashboard__component__title">
                        <div class="tag">
                            <p><i class="fa fa-chart-pie"></i>Recent</p>
                        </div>
                    </div>
                    <div class="dashboard__component__content recent">
                        <div class="recent__element">
                            <div class="recent__element__title">
                                <h1>Survey title</h1>
                            </div>
                            <div class="recent__element__answers">
                                <div class="recent__element__answers__element">
                                    <p>Answer 1</p>
                                    <div class="recent__element__answers__bar">
                                        <div class="recent__element__answers__bar__fill" style="width: 80%;"></div>
                                    </div>
                                </div>
                                <div class="recent__element__answers__element">
                                    <p>Answer 2</p>
                                    <div class="recent__element__answers__bar">
                                        <div class="recent__element__answers__bar__fill red" style="width: 20%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="dashboard__right">
            <div class="dashboard__component">
                <div class="dashboard__component__title">
                    <div class="tag">
                        <p><i class="fa fa-pen-paintbrush"></i> Creation</p>
                    </div>
                </div>
                <div class="dashboard__component__content creation__container">
                    <form action="../controller/survey.php" class="survey__create" method="POST">
                        <div class="creation">
                            <label for="title">
                                <p>Title</p>
                                <input id="title" name="name" type="text" placeholder="type your question here" autocomplete="off">
                            </label>
                            <label for="description">
                                <p>description (optional)</p>
                                <input id="description" name="description" type="text" autocomplete="off">
                            </label>
                            <input type="hidden" id="nb-choice" name="nb-choice" value="2">
                            <div id="creation__answers" class="creation__answers" name="type">
                                <p>answers options <i class="fa fa-plus" onclick="add__option()"></i></p>
                                <div class="creation__answers__input">
                                    <input type="text" name="choice-1" placeholder="option 1" autocomplete="off">
                                </div>
                                <div class="creation__answers__input">
                                    <input type="text" name="choice-2" placeholder="option 2" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="creation__settings">
                            <label for="publicity" class="creation__settings__content">
                                <p>show poll in recent</p>
                                <label for="publicity" class="checkbox">
                                    <input id="publicity" type="checkbox">
                                </label>
                            </label>
                            <label for="anonymous" class="creation__settings__content">
                                <p>required participant names</p>
                                <label for="anonymous" class="checkbox">
                                    <input id="anonymous" type="checkbox">
                                </label>
                            </label>
                        </div>
                        <div class="creation__buttons">
                            <button type="submit" class="tag tag--click">
                                <p>create</p>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="dashboard__component share">
                <div class="dashboard__component__title">
                    <div class="tag">
                        <p><i class="fa fa-share-nodes"></i>share</p>
                    </div>
                </div>
                <div class="dashboard__component__content">
                    <?php if (isset($survey_data) && !empty($survey_data)) : ?>
                        <div class="dashboard__component__content__lign share">
                            <input onclick="copy__link()" type="text" readonly value="https://www.ourges.fr/survey/?token=<?= $_GET['token'] ?>">
                            <i class="fa fa-copy" title="copy to clipboard" onclick="copy__link()"></i>
                        </div>
                    <?php else : ?>
                        <p>Survey not found 🤷‍♂️</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>

</html>