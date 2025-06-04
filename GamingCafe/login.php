<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Cafe</title>
    <link rel="icon" href="css/img/joystick.png" type="image/icon type">
    <link rel="stylesheet" href="css/login_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    
</head>

<body>
    <div class="loginarea">
        <div class="container">
            <div class="dec1 dec"></div>
            <div class="dec2 dec"></div>
            <div class="dec3 dec"></div>
            <form class="log" id="loginForm" method="POST">
                <div class="cvdec cvdec1"></div>
                <div class="cvdec cvdec2"></div>
                <div class="chdec chdec1"></div>
                <div class="chdec chdec2"></div>
                <div class="caption">LOGIN</div>
                <div class="text">User Name</div>
                <input type="text" name="uname" id="uname">
                <div class="error">
                </div>
                <div class="text">Password</div>
                <input type="password" name="password" id="password">

                <div class="error">
                <p id="errorMessage" style="color: red; display: none;"></p>
                </div>
                <input type="submit" id="loginbtn" value="Login" name="Submit">
            </form> 
        </div>
        <p><a href="register.php">Register</a></p>
    </div>
    <script src="js/login_page.js"></script>
</body>


</html>