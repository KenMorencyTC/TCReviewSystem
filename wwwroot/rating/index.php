<?php
header("Content-Type: application/json; charset=UTF-8");
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header(401);
    echo json_encode(array("message" => "Unauthorized"));
    exit;
}

require_once '../config/database.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
        //ADD NEW RATING/REVIEW
        $userid = $review = "";
        $rating = 0;
        $userid_err = $rating_err = $review_err = "";
        
        //VALIDATION
        $userid = trim($_POST["userid"]);
        if (empty($userid)) {
            $userid_err = "No userid provided. ";
        } elseif (strlen($userid) > 255) {
            $userid_err = "Userid is too long. ";
        }
        $rating = (int)($_POST["rating"]);
        if(empty($rating)){
            $rating_err = "No rating provided. ";
        } elseif ($rating > 3 or $rating < 1) {
            $rating_err = "Invalid rating value. ";
        }
        $review = trim($_POST["review"]);
        if (strlen($review) > 250) {
            $review_err = "Review is too long. ";
        }
        //ALTERNATE VALIDATION FOR MAKING A REVIEW REQUIRED.
        /*if (empty($review)) {
            $review_err = "No review provided. ";
        } elseif (strlen($review) > 250) {
            $review_err = "Review is too long. ";
        }*/
    
        //PROCESS POST REQUEST
        if(empty($review_err) && empty($rating_err) && empty($userid_err)){
            //XSS/MYSQL VULN PROTECTION
            $rating = $mysqli->real_escape_string(htmlspecialchars($rating));
            $review = $mysqli->real_escape_string(htmlspecialchars($review));
            $userid = $mysqli->real_escape_string(htmlspecialchars($userid));
            $sql = "INSERT INTO ratings (rating, review, userid) VALUES 
            ($rating,'$review','$userid')";
            if ($mysqli->query($sql) === TRUE) {
                header('HTTP/1.1 201 Created');
                echo json_encode(array("message" => "ReviewSaved"));
            } else {
                echo json_encode(array("message" =>  "Error " . $mysqli->error));
            }
        } else {
            echo json_encode(array("message" =>  "Malformed request: " . $userid_err . $rating_err . $review_err));
        }
} else {
    if(isset($_GET['id']) && !empty($_GET['id'])){
        //GET ONE RATING/REVIEW
        $stmt = $mysqli->prepare("SELECT id, rating, review, userid, reg_date FROM ratings WHERE id=".$_GET['id'] );
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $outp = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($outp);
        } else {
            echo json_encode(array("message" => "No Review Found With That ID."));
        }
        $stmt->close();
    } else {
        //GET ALL RATINGS/REVIEWS
        $stmt = $mysqli->prepare("SELECT id, rating, review, userid, reg_date FROM ratings");
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $outp = $result->fetch_all(MYSQLI_ASSOC);
            echo json_encode($outp);
        } else {
            echo json_encode(array("message" => "No Reviews Found."));
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>