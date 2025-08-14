<?php
	$heading = isset($heading) ? $heading : "??";
	$outTxt = isset($outTxt) ? $outTxt : "send something first";
	$backpage = isset($backpage) ? "../$backpage" : "javascript:history.back()";
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Response for <?=$heading?></title>

	<link rel="stylesheet" type="text/css" href="fdbk.css">
	<link rel="stylesheet" type="text/css" href="w3.css">
	<style>
		:root {
			--box-width: 300px;
			--box-height: 200px;
			--border-radius: 15px;
			--box-shadow: 0 0px 20px rgba(12, 169, 64, 0.2);
			--background-color: var(--bodybg);
		}

		* {
			box-sizing: border-box;
			margin: 0;
			padding: 0;
		}

		body, html {
			height: 100%;
			font-family: Arial, sans-serif;
			/*background-color: #f0f0f0;*/
		}
		a{
			color: #fff !important;
		}

		.center-container {
			display: flex;
			justify-content: center;
			align-items: center;
			height: 100%;
		}

		.center-box {
			width: var(--box-width);
			background-color: var(--background-color);
			border-radius: var(--border-radius);
			box-shadow: var(--box-shadow);
			padding: 24px;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			text-align: center;
			gap: 20px;
		}
	</style>
</head>
<body>
	<div class="center-container">
		<div class="center-box">
			<h2><?=$heading?></h2>
			<p><?=$outTxt?></p>
			<a href="<?=$backpage?>" class="w3-btn themebtn">go back</a>
		</div>
	</div>
</body>
</html>