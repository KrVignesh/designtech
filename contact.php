<?php
define('SERVERNAME', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DBNAME', 'designtech');
error_reporting(0);

if ($_POST['reqType'] == 'contactMessage') {
    contactMessage();
} elseif ($_POST['reqType'] == 'getProjects') {
    getProjects();
} elseif ($_POST['reqType'] == 'getReviews') {
    getReviews();
}

function contactMessage()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $link = mysqli_connect("localhost", "username", "mpassword", "database");

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    date_default_timezone_set("Asia/Kolkata");
    $currDateTime = date('Y-m-d h:i:s');

    $sql = "INSERT INTO messages (name, email, message, inserted_datetime) VALUES ('" . $name . "', '" . $email . "', '" . $message . "', '$currDateTime') ";
    $result = $conn->query($sql);

    if ($result) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR');
        echo json_encode($finalResponse);
    }

    $conn->close();
}

function getProjects()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM projects";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
    $conn->close();
}

function getReviews()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM reviews LIMIT 5";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
    $conn->close();
}
