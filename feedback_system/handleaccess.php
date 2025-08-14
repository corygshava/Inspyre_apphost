<?php
	// default data
		// who sent the feedback, you can add an input with the name alias to ignore this
		$default_name = "visitor";
		// the heading for the message
		$heading = "Attempting login";
		// no need to change
		$outTxt = "started running";
		// what will be shown if it works
		$success_message = "feedback recorded i'll get back to you if necessary";
		// where to go back to
		$backpage = "./";
		// change to false if you want it to be instant
		$showMessage = true;

	// Enable output buffering to handle headers and cookies properly
	ob_start();

	// Load credentials from JSON file
	$saved_creds = 'creds.json';
	$errorhappened = false;

	if (!file_exists($saved_creds)) {
	    $outTxt = "Credentials file not found.<br>";
	    $errorhappened = true;
	}

	$rawdata = file_get_contents($saved_creds);
	$creds = json_decode($rawdata);

	if (json_last_error() !== JSON_ERROR_NONE) {
	    $outTxt = "Error decoding JSON.<br>";
	    $errorhappened = true;
	}

	// Check if POST data is sent
	if (isset($_POST['thename'],$_POST['thepass']) && !$errorhappened) {
	    $uname = $_POST['thename'];
	    $upass = $_POST['thepass'];

	    // Validate against stored credentials
	    if ($uname == $creds->username) {
	        if ($upass == $creds->password) {
	            // Set a cookie on successful login
	            $thetext = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890-_"), 0,4);
	            setcookie('user_access_available', "$thetext", time() + 3600);
	            $outTxt = "Login successful!";
	        } else {
	            $outTxt = "Wrong password.";
	        }
	    } else {
	        $outTxt = "Username not found.";
	    }
	} else {
	    $outTxt = "Invalid data sent.";
	}

	ob_end_flush();

	$outTxt .= "<br>redirecting in 2 secs";
	header("refresh:2;url=viewfeedback.php");

	include 'response.php';
?>