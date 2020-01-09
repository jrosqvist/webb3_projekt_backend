<?php
/* Joakim Rosqvist - Mittuniversitetet - 2019 */

class Job
{
    private $db;

    // Konstruerare som skapar en databasanslutning
    function __construct()
    {
        // Skapar en databasanslutning - använder konstanter från config.php
        $this->db = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME) or die('Fel vid anslutning');
    }

    // Hämtar all utbildnings-poster i databasen och returnerar dem
    function getJobs()
    {
        $sql = "SELECT id, workplace, title, startdate, enddate FROM jobs";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Hämtar en specifik jobb-post via id:t
    function getSingleJob($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "SELECT id, workplace, title, startdate, enddate FROM jobs 
        WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Lägger till en jobb-post till databasen
    function addJob($workplace, $title, $startdate, $enddate)
    {
        // Kontrollerar inmatad text
        if (
            !$this->setWorkplace($workplace) xor !$this->setTitle($title)
            xor !$this->setStartDate($startdate) xor !$this->setEndDate($enddate)
        ) {
            return false;
        }
        $sql = "INSERT INTO jobs (workplace, title, startdate, enddate) 
        VALUES('$workplace', '$title', '$startdate', '$enddate')";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Uppdaterar en jobb-post i databasen via id:t
    function updateJob($workplace, $title, $startdate, $enddate, $id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        // Kontrollerar inmatad text
        if (
            !$this->setWorkplace($workplace) xor !$this->setTitle($title)
            xor !$this->setStartDate($startdate) xor !$this->setEndDate($enddate)
        ) {
            return false;
        }
        $sql = "UPDATE jobs SET workplace = '$workplace', title = '$title', 
        startdate = '$startdate', enddate = '$enddate'
        WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Raderar en jobb-post med ID:t som identifierare
    function deleteJob($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "DELETE FROM jobs WHERE id = " . $id . ";";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }


    /* Set-metoder - formaterar inskickad data */

    function setWorkplace($workplace)
    {
        if ($workplace != "") {
            // Tar bort icke-önskvärda tecken
            $this->workplace = $this->db->real_escape_string($workplace);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setTitle($title)
    {
        if ($title != "") {
            // Tar bort icke-önskvärda tecken
            $this->title = $this->db->real_escape_string($title);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setStartDate($startdate)
    {
        if ($startdate != "") {
            // Tar bort icke-önskvärda tecken
            $this->startdate = $this->db->real_escape_string($startdate);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setEndDate($enddate)
    {
        if ($enddate != "") {
            // Tar bort icke-önskvärda tecken
            $this->enddate = $this->db->real_escape_string($enddate);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }
}
