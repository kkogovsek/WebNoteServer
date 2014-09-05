<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function getSubjects() {
	if(!isset($_GET['name'])) {
		echo file_get_contents('../files/config/subjects.json');
	}
	else {
		$subjects = file_get_contents('../files/config/subjects.json');
		$subjects = json_decode($subjects, true);

		foreach ($subjects as $subject) {
			if($subject['name'] == $_GET['name']) {
				echo json_encode($subject);
				return;
			}
		}
		http_response_code(400);
		return;
	}
}

function postSubjects() {
	if(isset($_GET['name'])) {
		$subjects = file_get_contents('../files/config/subjects.json');
		$subjects = json_decode($subjects, true);
		if($subjects) {
            foreach ($subjects as $subject) {
                if($subject['name'] == $_GET['name']) {
                    http_response_code(400);
                    return;
                }
            }
		}
		else {
		    $subjects = array();
		}
		$id = uniqid();
		array_push($subjects, array('name' => $_GET['name'], 'id' => $id, 'notes'=>array()));
		file_put_contents('../files/config/subjects.json', json_encode($subjects));
		mkdir('../files/data/' . $id);
		http_response_code(200);
		echo "Successfully added";
	}
	else {
		http_response_code(400);
		return;
	}
}

switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		getSubjects();
	break;

	case 'POST':
		postSubjects();
	break;
}
//http_response_code(404);