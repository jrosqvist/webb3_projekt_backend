<?php

/* Joakim Rosqvist - Mittuniversitetet - 2019 */
class Education
{
    private $db;

    // Konstruerare som skapar en databasanslutning
    function __construct()
    {
        // Skapar en databasanslutning - använder konstanter från config.php
        $this->db = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME) or die('Fel vid anslutning');
    }

    // Hämtar all utbildnings-poster i databasen och returnerar dem
    function getEducation()
    {
        $sql = "SELECT id, hie, name, credits, startdate, enddate FROM education";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Hämtar en specifik utbildnings-post via id:t
    function getSingleEducation($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "SELECT id, hie, name, credits, startdate, enddate FROM education 
        WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Lägger till en utbildnings-post till databasen
    function addEducation($hie, $name, $credits, $startdate, $enddate)
    {
        // Kontrollerar inmatad text
        if (
            !$this->setHie($hie) xor !$this->setName($name)
            xor !$this->setCredits($credits) xor !$this->setStartDate($startdate) xor !$this->setEndDate($enddate)
        ) {
            return false;
        }
        $sql = "INSERT INTO education (hie, name, credits, startdate, enddate) 
        VALUES('$hie', '$name', $credits, '$startdate', '$enddate')";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Uppdaterar en utbildnings-post i databasen via id:t
    function updateEducation($hie, $name, $credits, $startdate, $enddate, $id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        // Kontrollerar inmatad text
        if (
            !$this->setHie($hie) xor !$this->setName($name)
            xor !$this->setCredits($credits) xor !$this->setStartDate($startdate) xor !$this->setEndDate($enddate)
        ) {
            return false;
        }
        $sql = "UPDATE education SET hie = '$hie', name = '$name', 
        credits = '$credits', startdate = '$startdate', enddate = '$enddate'
        WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Raderar en post med ID:t som identifierare
    function deleteEducation($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "DELETE FROM education WHERE id = " . $id . ";";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    /* Set-metoder - formaterar inskickad data */
    function setHie($hie)
    {
        if ($hie != "") {
            // Tar bort icke-önskvärda tecken
            $this->hie = $this->db->real_escape_string($hie);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setName($name)
    {
        if ($name != "") {
            // Tar bort icke-önskvärda tecken
            $this->name = $this->db->real_escape_string($name);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setCredits($credits)
    {
        if ($credits != "") {
            // Tar bort icke-önskvärda tecken
            $this->credits = $this->db->real_escape_string($credits);
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
