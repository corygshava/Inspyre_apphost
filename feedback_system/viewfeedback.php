<?php
	$thecookie = 'user_access_available';
	$udata = isset($_COOKIE[$thecookie]) ? $_COOKIE[$thecookie] : "nada";
	$classname = $udata == "nada" ? "" : "t2";
	$gottendata = "[]";

	if(isset($_GET['leave']) && $_GET['leave'] == "yes"){
		echo "logging out";
		setcookie($thecookie,"ewfwf",time() - 3600);
		header('location:./');
	}

	if($udata != "nada"){
		$fname = 'feedbackdata.json';
		$mydata = file_exists($fname) ? file_get_contents($fname) : "[]";
		$gottendata = $mydata;
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recorded feedback</title>

	<link rel="stylesheet" href="s-autoform.css">
	<link rel="stylesheet" href="fdbk.css">
	<link rel="stylesheet" href="w3.css">
</head>
<body class="<?=$classname?>">
	<?php
		if($udata == "nada"){
	?>
	<div class="mymodal showup" id="feedbackmodal" data-shown="0" style="display:flex;">
		<div class="modal-content">
			<h1 class="w3-center">Start a session</h1>
			<hr>
			<form action="handleaccess.php" class="s-autoform-2" method="post">
				<div class="input-holder">
					<label for="email">username</label>
					<input type="text" name="thename" id="email" placeholder="your set username" required>
				</div>
				<div class="input-holder">
					<label for="subject">password</label>
					<input type="password" name="thepass" id="subject" placeholder="your set password" required>
				</div>
				<div class="input-holder">
					<button class="themebtn themebg">start session</button>
					<button class="themebtn themebg"><i class="fa fa-send"></i> send message</button>
				</div>
			</form>
		</div>
	</div>
	<?php
		} else {
	?>
	<header class="w3-center">
		<h1>Recorded feedback</h1>
		<a href="viewfeedback.php?leave=yes" class="themebtn" style="padding:8px 12px;">logout</a>
	</header>
	<div class="datapart">
		<div class="searchbox"></div>
		<div class="cardbox" id="outdata">
			
		</div>
	</div>
	<script>
		let thedata = <?=$gottendata?>;

		window.onload = () => {
			init();
		}

		function init() {
			outdata.innerHTML = "";
			thedata.forEach(el => {
				outdata.innerHTML += `
					<div class="card">
				<div class="card-header">
					<div>
						<span class="bolden">${el.alias}</span>
					</div>
					<span class="subtxt">${el.timesent || "--"}</span>
				</div>
				<div class="card-content">
					<div><b>email : </b> ${el.email}</div>
					<div><b>subject : </b> ${el.subject}</div>
					<div>
						<b>message : </b> <br>
						<p>${el.msg}</p>
					</div>
					<div>
						<a href="mailto:${el.email}" class="w3-button themebtn">send reply</a>
						<p>what were they saying</p>
					</div>
					<div>
						<a href="mailto:${el.mail}" class="w3-button themebtn">send reply</a>
					</div>
				</div>
			</div>
				`;
			})
		}
	</script>
	<?php
		}
	?>

	<footer>
		<div class="footerstuff">
			<div>&copy; Feedback recorder</div>
			<div>&copy; CoryG</div>
		</div>
		<div>
			<a href="../" class="themebtn" style="padding:8px 12px;">go back</a>
		</div>
	</footer>

</body>
</html>