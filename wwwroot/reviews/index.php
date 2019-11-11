<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ./login.php");
    exit;
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Transport Canada Review System (DEMO)</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
    <script type="text/javascript" src="./js/jquery-3.4.1.min.js"></script>
    <script type="text/javascript">
    
    //Set Text Representation of Rating
    function ratingValue(i) {
        switch(i) {
        case 1:
            return "Excellent";
        case 2:
            return "Moderate";
        case 3:
            return "Needs Improvement";
        default:
            alert("Error formatting rating value.");
        } 
    }
    function sanitizeHTML(value) {
        var lt = "<", gt = ">", ap = "'", ic = '"', sc = ";";
        value = value.toString().replace(lt, "&lt;").replace(gt, "&gt;").replace(ap, "&#39;").replace(ic, "&#34;").replace(sc, "&#59;");
        return value;
    }
    $(document).ready(function() {
        var response, output, rating, review = ""
        var APIPath = "https://tc-demo-webapp.azurewebsites.net/rating/";
        //FETCH/ASSEMBLE REVIEWS FROM API
        function reloadReviews() {
            $.ajax({
                url: APIPath,
                dataType: 'json',
                data: response
            })
            .done(function(response) {
                output = ""
                if (response.message) {
                    alert(response.message)
                } else {
                    for (i in response) {
                        if (response[i].rating){
                            rating = ratingValue(response[i].rating)
                            review = sanitizeHTML(response[i].review).replace(/\n/g, "<br />")
                            output += '<a href="#img' + i + '"><div class="grid-item btn btn-success">' + rating + '</div></a>' + 
                            '<a href="#_" class="lightbox" id="img' + i + '"><div class="wrapper"><h3>' + rating + '</h3>' +  review + '</div></a>'
                        }
                    }
                }
                $("#reviews").html(output)
            })
            .fail(function(jqXHR, textStatus) {
                alert( "Request failed: " + textStatus );
            });
        }
        reloadReviews()
        //OVERRIDE SUBMIT W AJAX CALL
        $('form').submit(function(event) {
            var formData = {
                'userid' : $('#form-userid').val(),
                'rating' : $('#form-rating').val(),
                'review' : $('#form-review').val()
            };
            $.ajax({
                type        : 'POST',
                url         : APIPath,
                data        : formData,
                dataType    : 'json',
                encode      : true
            })
            .done(function(response) {
                if (response.message == "ReviewSaved") {
                    alert("Thank you for your review!")
                    reloadReviews()
                } else if (response.message == "Unauthorized") {
                    alert("Your session has expired, please login and try again.")
                } else {
                    alert("Error: " + response)
                }
            })
            .fail(function(jqXHR, textStatus) {
                alert( "Request failed: " + textStatus )
            });
            event.preventDefault()
        });
    });
    
    </script>
</head>
<body class="text-center">
    <div class="wrapper">
    <div class="page-header">
        <h1>Transport Canada Review System (DEMO)</h1>
    </div>
    <form action="#" class="form-small" id="form">
        <input type="hidden" name="form-userid" id="form-userid" value="<?php echo htmlspecialchars($_SESSION["username"]); ?>">
        <p>Please leave us a review about<br/>your online experience!</p>
        <div class="form-group">
            <label class="">Rating</label>
            <select class="form-control" name="form-rating" id="form-rating">
                <option value="1">Excellent</option>
                <option value="2">Moderate</option>
                <option value="3">Needs Improvement</option>
            </select>
        </div>    
        <div class="form-group">
            <label class="">Review</label>
            <textarea name="form-review" id="form-review" maxlength="250" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Submit Review" name="form-submit" id="form-submit">
        </div>
    </form>
    <div id="rating"><h3>User Reviews</h3></div>
    <div class="reviews grid-container" id="reviews"></div>
    <p><a href="./logout.php" class="btn btn-danger"><?php echo htmlspecialchars($_SESSION["username"]); ?> - Log out</a></p>
    </div>
</body>
</html>