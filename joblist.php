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

// Kollar om första elementet i sökvägen är jobs
if($request[0] != "jobs"){ 
	http_response_code(404);
	exit();
}

// Skapar en ny instans av klassen job
$job = new Job;

// Sparar inskickat data i variabler och klipper bort eventuella HTML-taggar
$workplace = strip_tags($input['workplace']);
$title = strip_tags($input['title']);
$startdate = strip_tags($input['startdate']);
$enddate = strip_tags($input['enddate']);

// Switch-sats som kör kod beroende på vilken metod som skickats
switch ($method){
	case "GET":
		// Hämtar ett specifikt jobb
		if (isset($request[1])) {
			$result = $job->getSingleJob($request[1]);
		break;
		} else {
	    // Hämtar alla jobb
	    $result = $job->getJobs();
		break;
		}
	case "POST":
	    // Lägger till jobb med de fyra inskickade argumenten
	    $result = $job->addJob($workplace, $title, $startdate, $enddate);
		break;
	case "DELETE":
	    // Tar bort ett jobb, id:t plockas från det andra elementet i URL:en
	    $result = $job->deleteJob($request[1]);
   		break;
	case "PUT":
	    // Uppdaterar ett specifik jobb som identifieras av id:t i URL:en
	    $result = $job->updateJob($workplace, $title, $startdate, $enddate, $request[1]);
    	break;
}

    // Skapar en tom array
	$job_arr = [];
	// Returnerar alla poster oavsett CRUD-metod
	if($method != "GET") {
		$result = $job->getJobs();
	}
		$job_arr = [];
			// Loopar igenom resultatet från switch-satsen
			while($row = mysqli_fetch_assoc($result)){
				$job_item = array(
					'id' => $row['id'],
					'workplace' => $row['workplace'],
					'title' => $row['title'],
					'startdate' => $row['startdate'],
					'enddate' => $row['enddate']
				);
				// Lägger in resultaten i den tomma arrayen
				array_push($job_arr,$job_item);
		}
		// Konverterar till JSON och skriver ut arrayen
		echo json_encode($job_arr);
