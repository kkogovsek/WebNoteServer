<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);

function getNotes() {
	if(isset($_GET['id']) && isset($_GET['subject'])) {

		echo '{"data":"'.urlencode(file_get_contents('../files/data/'.$_GET['subject'].'/'.$_GET['id'].'.md')).'"}';
	}
}

function postNotes() {
	if(isset($_GET['subject'])) {
        $id = time();
		if(isset($_GET['id'])) {
		    file_put_contents('../files/data/'.$_GET['subject'].'/'.$_GET['id'].'.md', $_GET['data']);
		}
		else {
            file_put_contents('../files/data/'.$_GET['subject'].'/'.$id.'.md', $_GET['data']);
            //file_put_contents('../files/data/'.$_GET['subject'].'/'.$id.'.md', json_decode(file_get_contents('php://input'))->data);
            $subjects = file_get_contents('../files/config/subjects.json');
            $subjects = json_decode($subjects);
            foreach ($subjects as $subject) {
                if($subject->id == $_GET['subject']) {
                	if($subject->notes == null) {
                		$subject->notes = array();
                	}
                    array_push($subject->notes, array('file'=>$id, 'title'=>$_GET['title']));
                    file_put_contents('../files/config/subjects.json', json_encode($subjects));
                }
            }
            echo '{"id": "'.$id.'"}';
		}
		http_response_code(200);
	}
	else {
		http_response_code(400);
		return;
	}
}

switch($_SERVER['REQUEST_METHOD']) {
	case 'GET':
		getNotes();
	break;

	case 'POST':
		postNotes();
	break;
}