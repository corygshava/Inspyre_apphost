<?php
    header('Content-Type: application/json');

    // Check for POST data
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        respond(false, 'Invalid request method.');
    }

    $gotten = file_get_contents("php://input");
    $passeddata = json_decode($gotten);

    $id = isset($_POST['id']) ? $_POST['id'] : (isset($passeddata->id) ? $passeddata->id : 'xxx');
    $providedKey = isset($_POST['apikey']) ? $_POST['apikey'] : (isset($passeddata->apikey) ? $passeddata->apikey : 'xxx');

    // Validate API key
    $storedKey = file_get_contents(__DIR__ . '/ddxrv_sitekey.txt');

    if ($providedKey != $storedKey) {
        respond(false, "Unauthorized: Invalid API key. [$providedKey vs $storedKey]");
    }

    // Validate ID
    if (!is_numeric($id) || $id < 0) {
        respond(false, 'Invalid ID.');
    }

    // Load JSON file
    $jsonFile = __DIR__ . '/itemslist.json';
    if (!file_exists($jsonFile)) {
        respond(false, 'Data file not found.');
    }

    $data = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($data)) {
        respond(false, 'Data file is corrupted.');
    }

    // Check if ID exists
    if (!isset($data[$id])) {
        respond(false, 'No item found with that ID.');
    }

    // Remove item
    unset($data[$id]);

    // Reindex array to maintain numeric order (optional)
    $data = array_values($data);

    // Save back to file
    if (file_put_contents($jsonFile, json_encode($data, JSON_PRETTY_PRINT)) === false) {
        respond(false, 'Failed to save changes.');
    }

    respond(true, 'Item deleted successfully.');
?>

<?php
    // Utility function to send a JSON response
    function respond($success, $message) {
        echo json_encode(['success' => $success, 'message' => $message]);
        exit;
    }
?>