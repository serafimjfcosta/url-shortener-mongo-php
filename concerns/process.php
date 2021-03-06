<?php

	// Creating short URL by POST request
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {

		$shortenerURL = new shortenerURL;
		$validate_request = $shortenerURL->validateURL($_POST['url']);
		
		if ( $validate_request === true ) {
			$response = $shortenerURL->createShortURL($_POST['url']);
			if (is_array($response)===true) {
				$id = $application->convertObjecttoString($response['_id']);

				$application->redirectToURL($application->getProjectPath().'post/'.$id);
				exit;
			}
		} else {
			$msg = $application->errorMessage($validate_request);
		}


	// Dispaying posted url results, to avoid resubmission
	} else if ( $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id']) && isset($_GET['action']) ) {

		
		$id = $_GET['id'];
		$shortenerURL = new shortenerURL;
		$response = $shortenerURL->getRecordById($id);
		if (is_array($response)===true) {
			$complete_url = $application->getTempShortURL($response['short_url']);
			$msg = $application->getLinkToDisplay($complete_url);
		} else {
			$msg = $application->errorMessage(200);
		}


	// Redirect to desired short URL destination
	} else if ( $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['short_code']) ) {
		
		$short_code = $_GET['short_code'];
		$shortenerURL = new shortenerURL;
		$record = $shortenerURL->getRecordByShortCode($short_code);

		echo $id = $application->convertObjecttoString($record['_id']);
		if ($id) { 
			$shortenerURL->updateViews($id);
			$application->redirectToURL($record['url']);
			exit;
		} else {
			$msg = $application->errorMessage(200);
		}
	} 
