<?php
	// === CONFIG ===
	$itemsFile = __DIR__ . '/itemslist.json';
	$keyFile   = __DIR__ . '/ddxrv_sitekey.txt';

	// === HELPER: respond & exit ===
	function respond($status, $msg) {
	    http_response_code($status ? 200 : 400);
	    echo $msg;
	    exit;
	}

	// === SECURITY: Check API key ===
	if (!file_exists($keyFile)) {
	    respond(false, "Server configuration error: key file missing.");
	}
	$serverKey = trim(file_get_contents($keyFile));
	$clientKey = $_POST['apikey'] ?? '';

	if (!$clientKey || !hash_equals($serverKey, $clientKey)) {
	    respond(false, "Invalid or missing API key.");
	}

	// === VALIDATE INPUTS ===
	$name = trim($_POST['name'] ?? '');
	$type = trim($_POST['type'] ?? '');
	$tags = trim($_POST['tags'] ?? '');
	$description = trim($_POST['description'] ?? '');
	$link = trim($_POST['link'] ?? '');

	// Check required fields
	if ($name === '' || $type === '' || $description === '' || $link === '') {
	    respond(false, "Missing required fields.");
	}

	// Validate type
	$validTypes = ['app', 'applet', 'tool'];
	if (!in_array(strtolower($type), $validTypes, true)) {
	    respond(false, "Invalid project type.");
	}

	// Validate link
	if (!filter_var($link, FILTER_VALIDATE_URL)) {
	    respond(false, "Invalid URL format.");
	}

	// Sanitize tags into an array
	$tagArray = array_filter(array_map('trim', explode(',', $tags)), fn($t) => $t !== '');

	// === PREPARE NEW ITEM ===
	$newItem = [
	    'name'        => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
	    'type'        => strtolower($type),
	    'tags'        => $tagArray,
	    'description' => htmlspecialchars($description, ENT_QUOTES, 'UTF-8'),
	    'link'        => $link,
	    'added'       => date('c') // ISO 8601 timestamp
	];

	// === LOAD EXISTING DATA OR INIT EMPTY ARRAY ===
	if (!file_exists($itemsFile)) {
	    file_put_contents($itemsFile, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
	}

	$jsonData = file_get_contents($itemsFile);
	$data = json_decode($jsonData, true);

	// Handle invalid JSON (corrupted file)
	if (!is_array($data)) {
	    $backupName = $itemsFile . '.bak_' . time();
	    rename($itemsFile, $backupName);
	    $data = [];
	}

	// === APPEND NEW ITEM & SAVE WITH LOCK ===
	$data[] = $newItem;
	$fileHandle = fopen($itemsFile, 'c+');
	if ($fileHandle === false) {
	    respond(false, "Unable to open items file for writing.");
	}
	if (flock($fileHandle, LOCK_EX)) {
	    ftruncate($fileHandle, 0);
	    fwrite($fileHandle, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
	    fflush($fileHandle);
	    flock($fileHandle, LOCK_UN);
	    fclose($fileHandle);
	    respond(true, "Project added successfully.");
	} else {
	    fclose($fileHandle);
	    respond(false, "Could not lock the items file.");
	}
?>