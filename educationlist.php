<?php
// Anger JSON som filtyp och UTF-8 som teckenkodning
//header("Content-Type: application/json");
header("Content-Type: application/json; charset=UTF-8");
// Tillåter åtkomst från samtliga domäner
header("Access-Control-Allow-Origin: *");
// TIllåter att metoderna POST, GET, DELETE och PUT används
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');

// Hämtar config-filen
include ("includes/config.php");
// Läser av vilken metod som skickats med i HTTP-anropet
$method = $_SERVER['REQUEST_METHOD'];

// Kollar om PATH_NFO inte är angiven, isf generera en 404-sida
if (!isset($_SERVER['PATH_INFO'])) {
	http_response_code(404);
	exit();
}

// Snyggar till sökvägen
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
// Läser av inmatat data och konverterar från JSON
$input = json_decode(file_get_contents('php://input'),true);

// Kollar om första elementet i sökvägen är courses
if($request[0] != "education"){ 
	http_response_code(404);
	exit();
}

// Skapar en ny instans av klassen Education
$education = new Education;

// Sparar inskickat data i variabler och klipper bort eventuella HTML-taggar
$hie = strip_tags($input['hie']);
$name = strip_tags($input['name']);
$credits = strip_tags($input['credits']);
$startdate = strip_tags($input['startdate']);
$enddate = strip_tags($input['enddate']);

// Switch-sats som kör kod beroende på vilken metod som skickats
switch ($method){
	case "GET":
		// Hämtar en kurs
		if (isset($request[1])) {
			$result = $education->getSingleEducation($request[1]);
		break;
		} else {
	    // Hämtar alla kurser
	    $result = $education->getEducation();
		break;
		}
	case "POST":
	    // Lägger till kurs med de fyra inskickade argumenten
	    $result = $education->addEducation($hie, $name, $credits, $startdate, $enddate);
		break;
	case "DELETE":
		$result = $education->deleteEducation($request[1]);
   		break;
	case "PUT":
	    // Uppdaterar en specifik kurs, som identifieras av id:t i URL:en
	    $result = $education->updateEducation($hie, $name, $credits, $startdate, $enddate, $request[1]);
    	break;
}

    // Skapar en tom array
	$education_arr = [];
	// Returnerar alla poster oavsett CRUD-metod
	if($method != "GET") {
		$result = $education->getEducation();
	}
		$education_arr = [];
			// Loopar igenom resultatet från switch-satsen
			while($row = mysqli_fetch_assoc($result)){
				$education_item = array(
					'id' => $row['id'],
					'hie' => $row['hie'],
					'name' => $row['name'],
					'credits' => $row['credits'],
					'startdate' => $row['startdate'],
					'enddate' => $row['enddate']
				);
				// Lägger in resultaten i den tomma arrayen
				array_push($education_arr,$education_item);
		}
		// Konverterar till JSON och skriver ut arrayen
		echo json_encode($education_arr);

?>