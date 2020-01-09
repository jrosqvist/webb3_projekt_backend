<?php
/* Joakim Rosqvist - Mittuniversitetet - 2019 */

// Anger JSON som filtyp och UTF-8 som teckenkodning
//header("Content-Type: application/json");
header("Content-Type: application/json; charset=UTF-8");
// Tillåter åtkomst från samtliga domäner
header("Access-Control-Allow-Origin: *");
// TIllåter att metoderna POST, GET, DELETE och PUT används
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT');
//header('Access-Control-Allow-Headers', "*");;

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

// Kollar om första elementet i sökvägen är webpages
if($request[0] != "webpages"){ 
	http_response_code(404);
	exit();
}

// Skapar en ny instans av klassen webpage
$webpage = new Webpage;

// Sparar inskickat data i variabler och klipper bort eventuella HTML-taggar
$title = strip_tags($input['title']);
$url = strip_tags($input['url']);
$description = strip_tags($input['description']);

// Switch-sats som kör kod beroende på vilken metod som skickats
switch ($method){
	case "GET":
		// Hämtar en specifik webbplats
		if (isset($request[1])) {
			$result = $webpage->getSingleWebpage($request[1]);
		break;
		} else {
	    // Hämtar alla webbplatser
	    $result = $webpage->getWebpages();
		break;
		}
	case "POST":
	    // Lägger till webbplats med de tre inskickade argumenten
	    $result = $webpage->addWebpage($title, $url, $description);
		break;
	case "DELETE":
	    // Tar bort en webbplats, id:t plockas från det andra elementet i URL:en
	    $result = $webpage->deleteWebpage($request[1]);
   		break;
	case "PUT":
	    // Uppdaterar en specifik webbplats, som identifieras av id:t i URL:en
	    $result = $webpage->updateWebpage($title, $url, $description, $request[1]);
    	break;
}

    // Skapar en tom array
	$webpage_arr = [];
	// Returnerar alla poster oavsett CRUD-metod
	if($method != "GET") {
		$result = $webpage->getWebpages();
	}
		$webpage_arr = [];
			// Loopar igenom resultatet från switch-satsen
			while($row = mysqli_fetch_assoc($result)){
				$webpage_item = array(
					'id' => $row['id'],
					'title' => $row['title'],
					'url' => $row['url'],
					'description' => $row['description']
				);
				// Lägger in resultaten i den tomma arrayen
				array_push($webpage_arr,$webpage_item);
		}
		// Konverterar till JSON och skriver ut arrayen
		echo json_encode($webpage_arr);

?>