<?php

class Survey
{
    private $token;
    private $name;
    private $description;
    private $type;
    private $nb_choice;
    private $user_id;
    private $public;
    private $anonymous;
    private $responses = [];
    private $choices = [];
    protected $survey_id;
    protected $connect;

    public function __construct()
    {
        require_once("Db.php");

        $database_object = new Db;

        $this->connect = $database_object->connect();
    }

    function setName($name)
    {
        $this->name = $name;
    }

    function getName()
    {
        return $this->name;
    }

    function setToken($token)
    {
        $this->token = $token;
    }

    function getToken()
    {
        return $this->token;
    }

    function setDescription($description)
    {
        $this->description = $description;
    }

    function getDescription()
    {
        return $this->description;
    }

    function setType($type)
    {
        $this->type = $type;
    }

    function getType()
    {
        return $this->user_id;
    }

    function setNbChoice($nb_choice)
    {
        $this->nb_choice = $nb_choice;
    }

    function getNbChoice()
    {
        return $this->nb_choice;
    }

    function setChoices($choice)
    {
        array_push($this->choices, $choice);
    }

    function getChoices()
    {
        return $this->choices;
    }

    function setResponses($response)
    {
        array_push($this->responses, $response);
    }

    function getResponses()
    {
        return $this->responses;
    }

    function setUser($user_id)
    {
        $this->user_id = $user_id;
    }

    function getUser()
    {
        return $this->user_id;
    }

    function setPublic($value)
    {
        $this->public = $value;
    }

    function setAnonymous($value)
    {
        $this->anonymous = $value;
    }


    function create_survey()
    {
        $req = $this->connect->prepare('INSERT INTO survey (token, name, description, type, nb_choice, owner_id, public, anonymous) VALUES (:token, :name, :description, :type, :nb_choice, :owner_id, :public, :anonymous)');
        $req->bindValue(':token', $this->token);
        $req->bindValue(':name', $this->name);
        $req->bindValue(':description', $this->description);
        $req->bindValue(':type', $this->type);
        $req->bindValue(':nb_choice', $this->nb_choice);
        $req->bindValue(':owner_id', $_SESSION['profile']->uid);
        $req->bindValue(':public', $this->public);
        $req->bindValue(':anonymous', $this->anonymous);
        $stmt = $req->execute();

        if (!isset($stmt)) {
            return false;
        }

        $getSurveyId = $this->connect->query('SELECT id FROM survey ORDER BY id DESC LIMIT 1');
        $this->survey_id = $getSurveyId->fetch(PDO::FETCH_ASSOC)['id'];

        foreach ($this->choices as $choice) {
            $createChoice = $this->connect->prepare('INSERT INTO survey_choices (choice, survey_id) VALUES (:choice, :survey_id)');
            $createChoice->bindValue(':choice', $choice);
            $createChoice->bindValue(':survey_id', $this->survey_id);
            $createChoice->execute();
        }

        return true;
    }

    function get_survey()
    {
        $req = $this->connect->prepare('SELECT token, name, description, type, nb_responses, anonymous FROM survey WHERE token = :token');
        $req->bindValue(':token', $this->token);
        $req->execute();
        $survey_data = $req->fetchAll(PDO::FETCH_ASSOC);

        if (isset($survey_data) && !empty($survey_data)) {
            return $survey_data[0];
        }

        return 0;
    }

    function get_choices()
    {
        $req = $this->connect->prepare('SELECT survey_choices.id, choice FROM survey_choices INNER JOIN survey ON survey.id = survey_choices.survey_id WHERE token = :token');
        $req->bindValue(':token', $this->token);
        $req->execute();
        $choices = $req->fetchAll(PDO::FETCH_ASSOC);

        if (isset($choices) && !empty($choices)) {
            return $choices;
        }

        return 0;
    }

    function send_response() {
        $responses = $this->getResponses();
        if(isset($responses) && !empty($responses)){
            foreach($responses as $response){
                $req = $this->connect->prepare('INSERT INTO survey_responses (choice_id, user_id) VALUES (:choice_id, :user_id)');
                $req->bindValue(':choice_id', intval($response));
                $req->bindValue(':user_id', $this->user_id);

                if($req->execute()){
                    $updateSurveyCount = $this->connect->prepare('UPDATE survey SET nb_responses = nb_responses + 1 WHERE token = :token');
                    $updateSurveyCount->bindValue(':token', $this->token);
                    $updateSurveyCount->execute();

                    $updateChoiceCount = $this->connect->prepare('UPDATE survey_choices SET nb_responses = nb_responses + 1 WHERE id = :choice_id');
                    $updateChoiceCount->bindValue(':choice_id', intval($response));
                    $updateChoiceCount->execute();
                };
            }

            return 1;
        }

        return 0;
    }

    function get_responses() {
        if(isset($this->token)){
            $req = $this->connect->prepare('SELECT ROUND((survey_choices.nb_responses / survey.nb_responses * 100), 1) as choice_percentage, survey_choices.nb_responses as nb_response, survey.nb_responses as total_response, survey_id, choice, survey_choices.id as choice_id FROM survey_choices INNER JOIN survey ON survey.id = survey_choices.survey_id WHERE survey.token = :token;');
            $req->bindValue(':token', $this->token);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

        return 0;
    }

    function get_response_from_user() {
        if(isset($this->token) && isset($this->user_id)){
            $req = $this->connect->prepare('SELECT choice FROM survey INNER JOIN survey_choices ON survey.id = survey_choices.survey_id INNER JOIN survey_responses ON survey_choices.id = survey_responses.choice_id WHERE survey.token = :token AND survey_responses.user_id = :user_id');
            $req->bindValue(':token', $this->token);
            $req->bindValue(':user_id', $this->user_id);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

        return 0;
    }

    function get_users_info() {
        if(isset($this->token)) {
            $req = $this->connect->prepare('SELECT chat_user.user_id, user_img, user_name, survey_choices.id as choice_id FROM survey_responses INNER JOIN survey_choices ON survey_responses.choice_id = survey_choices.id INNER JOIN chat_user ON survey_responses.user_id = chat_user.user_id INNER JOIN survey ON survey_choices.survey_id = survey.id WHERE token = :token');
            $req->bindValue(':token', $this->token);
            $req->execute();

            return $req->fetchAll(PDO::FETCH_ASSOC);
        }

        return 0;
    }

    function get_public_survey() {
        $req = $this->connect->prepare('SELECT id, token, name, DATEDIFF(NOW(), creation_date) as expiration FROM survey WHERE survey.public = 1 AND DATEDIFF(NOW(), creation_date) < 7');
        $req->execute();
        $all_survey = $req->fetchAll(PDO::FETCH_ASSOC);

        $req = $this->connect->prepare('SELECT ROUND((survey_choices.nb_responses / survey.nb_responses * 100), 1) as choice_percentage, survey_choices.nb_responses as nb_response, survey.nb_responses as total_response, survey_id, choice, survey_choices.id as choice_id FROM survey_choices INNER JOIN survey ON survey.id = survey_choices.survey_id WHERE survey.public = 1 AND DATEDIFF(NOW(), creation_date) < 7');
        $req->execute();
        $all_survey_responses = $req->fetchAll(PDO::FETCH_ASSOC);

        $surveys = [];

        foreach($all_survey as $survey) {
            $surveys[$survey['id']] = ["data" => $survey, "responses" => []];
        }

        // dd($surveys);
        foreach($all_survey_responses as $response) {
            array_push($surveys[$response['survey_id']]["responses"], $response);
        }

        return $surveys;
    }
}
