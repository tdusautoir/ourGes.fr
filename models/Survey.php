<?php

use function React\Promise\resolve;

class Survey
{
    private $token;
    private $name;
    private $description;
    private $type;
    private $nb_choice;
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

    function create_survey()
    {
        $req = $this->connect->prepare('INSERT INTO survey (token, name, description, type, nb_choice, owner_id) VALUES (:token, :name, :description, :type, :nb_choice, :owner_id)');
        $req->bindValue(':token', $this->token);
        $req->bindValue(':name', $this->name);
        $req->bindValue(':description', $this->description);
        $req->bindValue(':type', $this->type);
        $req->bindValue(':nb_choice', $this->nb_choice);
        $req->bindValue(':owner_id', $_SESSION['profile']->uid);
        $stmt = $req->execute();

        $getSurveyId = $this->connect->query('SELECT id FROM survey ORDER BY id DESC LIMIT 1');
        $this->survey_id = $getSurveyId->fetch(PDO::FETCH_ASSOC)['id'];

        if (!isset($stmt)) {
            return false;
        }

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
        $req = $this->connect->prepare('SELECT token, name, description, type FROM survey WHERE token = :token');
        $req->bindValue(':token', $this->token);
        $stmt = $req->execute();
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
        $stmt = $req->execute();
        $choices = $req->fetchAll(PDO::FETCH_ASSOC);

        if (isset($choices) && !empty($choices)) {
            return $choices;
        }

        return 0;
    }
}
