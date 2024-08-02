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

                // Store data in session for displaying later
                session_start();
                $_SESSION['NIN'] = $NIN;
                $_SESSION['firstName'] = $firstName;
                $_SESSION['middleName'] = $middleName;
                $_SESSION['surname'] = $surname;
                $_SESSION['sex'] = $sex;
                $_SESSION['dateOfBirth'] = $dateOfBirth;
                $_SESSION['nationality'] = $nationality;
                
                header("Location: result.php");
                exit();
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
    <title>Nida Information</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h1>Nida Information</h1>
        <div class="info">
            <?php
            session_start();
            if (isset($_SESSION['NIN'])) {
                echo "<p><strong>NIN:</strong> " . htmlspecialchars($_SESSION['NIN']) . "</p>";
                echo "<p><strong>First Name:</strong> " . htmlspecialchars($_SESSION['firstName']) . "</p>";
                echo "<p><strong>Middle Name:</strong> " . htmlspecialchars($_SESSION['middleName']) . "</p>";
                echo "<p><strong>Surname:</strong> " . htmlspecialchars($_SESSION['surname']) . "</p>";
                echo "<p><strong>Sex:</strong> " . htmlspecialchars($_SESSION['sex']) . "</p>";
                echo "<p><strong>Date of Birth:</strong> " . htmlspecialchars($_SESSION['dateOfBirth']) . "</p>";
                echo "<p><strong>Nationality:</strong> " . htmlspecialchars($_SESSION['nationality']) . "</p>";
            } else {
                echo "<p>No data found.</p>";
            }
            ?>
        </div>
        <div class="back-btn">
            <a href="index.php" class="button">Go back</a>
        </div>
    </div>
    <footer>
        <p>Dev By Sam Ochu</p>
    </footer>
</body>
</html>
