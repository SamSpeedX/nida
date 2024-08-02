<?php

function makePostRequest($NIN) {
    $url = 'https://ors.brela.go.tz/um/load/load_nida/' . $NIN;

    $headers = [
        'Content-Length: 0',
        'Content-type: application/json'
    ];

    $options = [
        'http' => [
            'header' => implode("\r\n", $headers),
            'method' => 'POST'
        ]
    ];

    $context = stream_context_create($options);
    $response = file_get_contents($url, false, $context);

    if ($response !== FALSE) {
        $statusCode = intval(explode(' ', $http_response_header[0])[1]);
        if ($statusCode == 200) {
            echo "Request was successful\n";

            $responseData = json_decode($response, true);
            if ($responseData !== NULL) {
                echo "First Name: " . htmlspecialchars($responseData['first']) . "<br>";
                echo "Last Name: " . htmlspecialchars($responseData['last']) . "<br>";

                if (isset($responseData['image'])) {
                    echo '<img src="data:image/jpeg;base64,' . htmlspecialchars($responseData['image']) . '" alt="Person Image">';
                } else {
                    echo "No image available for this person.";
                }
            } else {
                echo "Failed to decode JSON response.\n";
            }
        } else {
            echo "Failed to make request. Status code: " . $statusCode . "\n";
            echo "Response: " . htmlspecialchars($response) . "\n";
        }
    } else {
        echo "Failed to make request.\n";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nida'])) {
    $NIN = $_POST['nida'];
    makePostRequest($NIN);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page title</title>
</head>
<body>
    <form action="nida.php" method="post">
        <input type="text" name="nida" id="nida" placeholder="Enter your NIDA ID">
        <br>
        <button type="submit">Check</button>
    </form>
</body>
</html>
