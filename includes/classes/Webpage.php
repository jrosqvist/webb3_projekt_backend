<?php
/* Joakim Rosqvist - Mittuniversitetet - 2019 */

class Webpage
{
    private $db;

    // Konstruerare som skapar en databasanslutning
    function __construct()
    {
        // Skapar en databasanslutning - använder konstanter från config.php
        $this->db = mysqli_connect(HOST, USERNAME, PASSWORD, DBNAME) or die('Fel vid anslutning');
    }

    // Hämtar all webbplats-poster i databasen och returnerar dem
    function getWebpages()
    {
        $sql = "SELECT id, title, url, description FROM webpages";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Hämtar en specifik webbplats-post via id:t
    function getSingleWebpage($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "SELECT id, title, url, description FROM webpages 
        WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga - get');
        return $result;
    }

    // Lägger till en webbplats-post till databasen
    function addWebpage($title, $url, $description)
    {
        // Kontrollerar inmatad text
        if (
            !$this->setTitle($title) xor !$this->setUrl($url) xor !$this->setDescription($description)
        ) {
            return false;
        }
        $sql = "INSERT INTO webpages (title, url, description) 
        VALUES('$title', '$url', '$description')";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Uppdaterar en webbplats-post i databasen via id:t
    function updateWebpage($title, $url, $description, $id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        // Kontrollerar inmatad text
        if (
            !$this->setTitle($title) xor !$this->setUrl($url) xor !$this->setDescription($description)
        ) {
            return false;
        }
        $sql = "UPDATE webpages SET title = '$title', url = '$url', 
        description = '$description' WHERE id = $id";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    // Raderar en webbplats med ID:t som identifierare
    function deleteWebpage($id)
    {
        // Säkertställer att id:t är ett heltal (blir 0 om fel)
        $id = intval($id);
        $sql = "DELETE FROM webpages WHERE id = " . $id . ";";
        $result = mysqli_query($this->db, $sql) or die('Fel vid SQL-fråga');
        return $result;
    }

    /* Set-metoder - formaterar inskickad data */

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

    function setUrl($url)
    {
        if ($url != "") {
            // Tar bort icke-önskvärda tecken
            $this->url = $this->db->real_escape_string($url);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }

    function setDescription($description)
    {
        if ($description != "") {
            // Tar bort icke-önskvärda tecken
            $this->$description = $this->db->real_escape_string($description);
            return true;
        } else {
            // Om indatat är tomt
            return false;
        }
    }
}
