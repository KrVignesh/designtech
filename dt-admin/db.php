<?php
define('SERVERNAME', 'localhost');
define('USERNAME', 'root');
define('PASSWORD', '');
define('DBNAME', 'designtech');
define('REVIEWPHOTOUPLOADDIR', '../photos/reviewer_images/');
define('PROJECTPHOTOUPLOADDIR', '../photos/project_images/');
define('REVIEWPHOTODIR', 'http://localhost/designTech/photos/reviewer_images/');
define('PROJECTPHOTODIR', 'http://localhost/designTech/photos/project_images/');

if ($_POST['reqType'] == 'addReview') {
    addReview();
} else if ($_POST['reqType'] == 'getReviews') {
    getReviews();
} else if ($_POST['reqType'] == 'deleteReview') {
    deleteReview();
} else if ($_POST['reqType'] == 'getSingleRow') {
    getSingleRow();
} else if ($_POST['reqType'] == 'getContactMessages') {
    getContactMessages();
} else if ($_POST['reqType'] == 'deleteMessage') {
    deleteMessage();
} else if ($_POST['reqType'] == 'addProject') {
    addProject();
} else if ($_POST['reqType'] == 'getProjects') {
    getProjects();
} else if ($_POST['reqType'] == 'getSingleProjects') {
    getSingleProjects();
} else if ($_POST['reqType'] == 'deleteProject') {
    deleteProject();
} else if ($_POST['reqType'] == 'updateProject') {
    updateProject();
} else if ($_POST['reqType'] == 'login') {
    login();
} else if ($_POST['reqType'] == 'updateReview') {
    updateReview();
}

function addReview()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $name = $_FILES['photo']['name'];
    $target_dir = REVIEWPHOTOUPLOADDIR;
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {
        // Upload file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_dir . $name)) {
            $message = addslashes($_POST['message']);
            $sql = "INSERT INTO reviews (reviewer_name, reviewer_position, reviewer_message, reviewer_photo) VALUES ('" . $_POST['name'] . "', '" . $_POST['position'] . "','" . $message . "','" . REVIEWPHOTODIR . $name . "')";
            $result = $conn->query($sql);

            if ($result) {
                $finalResponse = array('status' => 'OK');
                echo json_encode($finalResponse);
            } else {
                $finalResponse = array('status' => 'ERROR', 'message' => 'Something went wrong!');
                echo json_encode($finalResponse);
            }
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Unable to upload file!');
            echo json_encode($finalResponse);
        }
    } else {
        $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
        echo json_encode($finalResponse);
    }
    $conn->close();
}

function getReviews()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT id, reviewer_name, reviewer_position, reviewer_message, reviewer_photo FROM reviews";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
    $conn->close();
}

function deleteReview()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM reviews WHERE id='" . $_POST['reviewId'] . "'";

    if ($conn->query($sql) === TRUE) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR');
        echo json_encode($finalResponse);
    }

    $conn->close();
}

function updateReview()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $message = addslashes($_POST['eMessage']);

    $sql = "UPDATE reviews SET reviewer_name = '" . $_POST['eName'] . "', reviewer_position = '" . $_POST['ePosition'] . "', reviewer_message = '" . $message . "' WHERE id = '" . $_POST['reviewId'] . "'";
    $result = $conn->query($sql);

    if ($result) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR', 'message' => 'Something went wrong!');
        echo json_encode($finalResponse);
    }

    $conn->close();
}

function getSingleRow()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM " . $_POST['tblName'] . " where id = " . $_POST['id'] . "";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        echo json_encode(mysqli_fetch_assoc($result));
    }
    $conn->close();
}

function getContactMessages()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM messages";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
    $conn->close();
}

function deleteMessage()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM messages WHERE id='" . $_POST['messageId'] . "'";

    if ($conn->query($sql) === TRUE) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR');
        echo json_encode($finalResponse);
    }

    $conn->close();
}

function addProject()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $target_dir = PROJECTPHOTOUPLOADDIR;
    $extensions_arr = array("jpg", "jpeg", "png");

    if (!empty($_FILES['photo1']['name'])) {
        $name1 = $_FILES['photo1']['name'];
        $target_file1 = $target_dir . basename($_FILES["photo1"]["name"]);
        $imageFileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));
        if (in_array($imageFileType1, $extensions_arr)) {
            move_uploaded_file($_FILES['photo1']['tmp_name'], $target_dir . $name1);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name1 = '';
    }
    if (!empty($_FILES['photo2']['name'])) {
        $name2 = $_FILES['photo2']['name'];
        $target_file2 = $target_dir . basename($_FILES["photo2"]["name"]);
        $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
        if (in_array($imageFileType2, $extensions_arr)) {
            move_uploaded_file($_FILES['photo2']['tmp_name'], $target_dir . $name2);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name2 = '';
    }
    if (!empty($_FILES['photo3']['name'])) {
        $name3 = $_FILES['photo3']['name'];
        $target_file3 = $target_dir . basename($_FILES["photo3"]["name"]);
        $imageFileType3 = strtolower(pathinfo($target_file3, PATHINFO_EXTENSION));
        if (in_array($imageFileType3, $extensions_arr)) {
            move_uploaded_file($_FILES['photo3']['tmp_name'], $target_dir . $name3);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name3 = '';
    }
    if (!empty($_FILES['photo4']['name'])) {
        $name4 = $_FILES['photo4']['name'];
        $target_file4 = $target_dir . basename($_FILES["photo4"]["name"]);
        $imageFileType4 = strtolower(pathinfo($target_file4, PATHINFO_EXTENSION));
        if (in_array($imageFileType4, $extensions_arr)) {
            move_uploaded_file($_FILES['photo4']['tmp_name'], $target_dir . $name4);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name4 = '';
    }

    $name = addslashes($_POST['name']);
    $description = addslashes($_POST['description']);
    $image_1 = empty($name1) ? '' : PROJECTPHOTODIR . $name1;
    $image_2 = empty($name2) ? '' : PROJECTPHOTODIR . $name2;
    $image_3 = empty($name3) ? '' : PROJECTPHOTODIR . $name3;
    $image_4 = empty($name4) ? '' : PROJECTPHOTODIR . $name4;

    $sql = "INSERT INTO projects (project_name, category, project_description, image_1, image_2, image_3, image_4, video_url) VALUES ('" . $name . "', '" . $_POST['category'] . "','" . $description . "','" . $image_1 . "','" . $image_2 . "','" . $image_3 . "','" . $image_4 . "','" . $_POST['video'] . "')";
    $result = $conn->query($sql);

    if ($result) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR', 'message' => 'Something went wrong!');
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

function getSingleProjects()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM projects WHERE id = '" . $_POST['projectId'] . "'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($r = mysqli_fetch_assoc($result)) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
    $conn->close();
}

function deleteProject()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM projects WHERE id='" . $_POST['projectId'] . "'";

    if ($conn->query($sql) === TRUE) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR');
        echo json_encode($finalResponse);
    }

    $conn->close();
}

function updateProject()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $target_dir = PROJECTPHOTOUPLOADDIR;
    $extensions_arr = array("jpg", "jpeg", "png");

    if (!empty($_FILES['photo1']['name'])) {
        $name1 = $_FILES['photo1']['name'];
        $target_file1 = $target_dir . basename($_FILES["photo1"]["name"]);
        $imageFileType1 = strtolower(pathinfo($target_file1, PATHINFO_EXTENSION));
        if (in_array($imageFileType1, $extensions_arr)) {
            move_uploaded_file($_FILES['photo1']['tmp_name'], $target_dir . $name1);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name1 = (!empty($_POST['newphoto1'])) ? (explode("/", $_POST['newphoto1'])[6]) : '';
    }
    if (!empty($_FILES['photo2']['name'])) {
        $name2 = $_FILES['photo2']['name'];
        $target_file2 = $target_dir . basename($_FILES["photo2"]["name"]);
        $imageFileType2 = strtolower(pathinfo($target_file2, PATHINFO_EXTENSION));
        if (in_array($imageFileType2, $extensions_arr)) {
            move_uploaded_file($_FILES['photo2']['tmp_name'], $target_dir . $name2);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name2 = (!empty($_POST['newphoto2'])) ? (explode("/", $_POST['newphoto2'])[6]) : '';
    }
    if (!empty($_FILES['photo3']['name'])) {
        $name3 = $_FILES['photo3']['name'];
        $target_file3 = $target_dir . basename($_FILES["photo3"]["name"]);
        $imageFileType3 = strtolower(pathinfo($target_file3, PATHINFO_EXTENSION));
        if (in_array($imageFileType3, $extensions_arr)) {
            move_uploaded_file($_FILES['photo3']['tmp_name'], $target_dir . $name3);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name3 = (!empty($_POST['newphoto3'])) ? (explode("/", $_POST['newphoto3'])[6]) : '';
    }
    if (!empty($_FILES['photo4']['name'])) {
        $name4 = $_FILES['photo4']['name'];
        $target_file4 = $target_dir . basename($_FILES["photo4"]["name"]);
        $imageFileType4 = strtolower(pathinfo($target_file4, PATHINFO_EXTENSION));
        if (in_array($imageFileType4, $extensions_arr)) {
            move_uploaded_file($_FILES['photo4']['tmp_name'], $target_dir . $name4);
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid file!');
            echo json_encode($finalResponse);
        }
    } else {
        $name4 = (!empty($_POST['newphoto4'])) ? (explode("/", $_POST['newphoto4'])[6]) : '';
    }

    $name = addslashes($_POST['eName']);
    $description = addslashes($_POST['eDescription']);
    $image_1 = empty($name1) ? '' : PROJECTPHOTODIR . $name1;
    $image_2 = empty($name2) ? '' : PROJECTPHOTODIR . $name2;
    $image_3 = empty($name3) ? '' : PROJECTPHOTODIR . $name3;
    $image_4 = empty($name4) ? '' : PROJECTPHOTODIR . $name4;

    // $sql = "INSERT INTO projects (project_name, category, project_description, image_1, image_2, image_3, image_4, video_url) VALUES ('" . $name . "', '" . $_POST['category'] . "','" . $description . "','" . $image_1 . "','" . $image_2 . "','" . $image_3 . "','" . $image_4 . "','" . $_POST['video'] . "')";
    $sql = "UPDATE projects SET project_name = '" . $name . "', category = '" . $_POST['eCategory'] . "', project_description = '" . $description . "', image_1 = '" . $image_1 . "',image_2 = '" . $image_2 . "',image_3 = '" . $image_3 . "',image_4 = '" . $image_4 . "', video_url = '" . $_POST['eVideo'] . "' WHERE id = '" . $_POST['projectId'] . "'";
    $result = $conn->query($sql);

    if ($result) {
        $finalResponse = array('status' => 'OK');
        echo json_encode($finalResponse);
    } else {
        $finalResponse = array('status' => 'ERROR', 'message' => 'Something went wrong!');
        echo json_encode($finalResponse);
    }


    $conn->close();
}

function login()
{
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DBNAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM users WHERE username = '" . $_POST['username'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $res = mysqli_fetch_assoc($result);
        if (sha1($_POST['password']) == $res['password']) {
            $finalResponse = array('status' => 'OK', 'message' => 'Login Success');
        } else {
            $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid Username/Password!');
        }
    } else {
        $finalResponse = array('status' => 'ERROR', 'message' => 'Invalid Username/Password!');
    }
    echo json_encode($finalResponse);
}
