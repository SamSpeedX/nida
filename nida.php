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
            $responseData = json_decode($response, true);
            if ($responseData !== NULL) {
                $result = $responseData['obj']['result'];
                $NIN = $result['NIN'];
                $firstName = $result['FIRSTNAME'];
                $middleName = $result['MIDDLENAME'];
                $surname = $result['SURNAME'];
                $sex = $result['SEX'];
                $dateOfBirth = $result['DATEOFBIRTH'];
                $nationality = $result['NATIONALITY'];

                echo "NIN: " . htmlspecialchars($NIN) . "<br>";
                echo "First Name: " . htmlspecialchars($firstName) . "<br>";
                echo "Middle Name: " . htmlspecialchars($middleName) . "<br>";
                echo "Surname: " . htmlspecialchars($surname) . "<br>";
                echo "Sex: " . htmlspecialchars($sex) . "<br>";
                echo "Date of Birth: " . htmlspecialchars($dateOfBirth) . "<br>";
                echo "Nationality: " . htmlspecialchars($nationality) . "<br>";
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
    <form action="index.php" method="post">
        <input type="text" name="nida" id="nida" placeholder="Enter your NIDA ID">
        <br>
        <button type="submit">Check</button>
    </form>
</body>
</html>
