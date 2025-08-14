<?php
	// default data
	$default_name = "visitor";		// who sent the feedback, you can add an input with the name alias to ignore this
	$default_subject = "feedback";		// who sent the feedback, you can add an input with the name alias to ignore this

	$heading = "Feedback not recorded";	// the heading for the message
	$outTxt = "started running";	// no need to change
	$success_message = "feedback recorded i'll get back to you if necessary"; // what will be shown if it works
	$backpage = "";					// where to go back to
	$showMessage = true;			// change to false if you want it to be instant

	// feedback data
	class data_blueprint{
		public $email = "";
		public $subject = "";
		public $alias = "";
		public $msg = "";
		public $timesent = "";

		public function __construct($mail,$sub,$alias,$msg){
			$this->email = $mail;
			$this->subject = $sub;
			$this->alias = $alias;
			$this->msg = $msg;

			$tym = Date('m-d-y h:i');
			$this->timesent = $tym;
		}
	}

	// the logic for processing the feedback data
	if (isset($_POST['theemail'],$_POST['themsg'])) {
		// get post data
		$theemail = $_POST['theemail'];
		$thesubject = isset($_POST['thesubject']) ? $_POST['thesubject'] : $default_subject;
		$themsg = $_POST['themsg'];
		$thealias = isset($_POST['alias']) ? $_POST['alias'] : $default_name;

		// get saved data, if it exists
		$thefile = 'feedbackdata.json';
		$ismade = is_file($thefile);
		$thedata = $ismade ? file_get_contents($thefile) : "[]";
		$thedata = $thedata == "" ? "[]" : $thedata;

		// add the new data
		$thelist = json_decode($thedata);
		$to_add = new data_blueprint($theemail,$thesubject,$thealias,$themsg);

		array_push($thelist, $to_add);
		$newdata = json_encode($thelist,JSON_PRETTY_PRINT,4);

		// write to the JSON file
		try{
			$myfile = fopen($thefile, "w");

			fwrite($myfile, $newdata);

			fclose($myfile);

			$heading = "Feedback recorded";
			$outTxt = "data recorded successfully";
		} catch(Exception $error){
			$outTxt = "an error occured while writing to the file, please try again later";
		}
	} else {
		$outTxt = "invalid request";
	}

	if($showMessage){
		include 'response.php';
	} else {
		header("Location:../$backpage?res=$outTxt");
	}
?>