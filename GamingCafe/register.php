
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Cafe</title>
    <link rel="icon" href="css/img/joystick.png" type="image/icon type">
    <link rel="stylesheet" href="css/registration_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="js/reg_page.js"></script>
</head>

<body>
    <div class="loginarea">
        <div class="container">
        <div class="dec1 dec"></div>
            <div class="dec2 dec"></div>
            <div class="dec3 dec"></div>
            <form method="POST" id="registerForm" class="log">       
            <input type="hidden" name="action" value="create">
            <div class="cvdec cvdec1"></div>
            <div class="cvdec cvdec2"></div>
                <div class="chdec chdec1"></div>
                <div class="chdec chdec2"></div>
                <div class="caption">Register</div>

                <div class="row1">
                    <div class="col1">
                        <div class="text">First Name</div>
                        <input type="text" name="first_name" id="fname"
           required
           pattern="^[A-Za-z]{2,}$"
           title="First name should contain only letters (no numbers or symbols, at least 2 letters)">
                        <div class="error" id="fnameError">
                        </div>
                    </div>
                    <div class="col1">
                        <div class="text">Last Name</div>
                        <input type="text" name="last_name" id="lname" required pattern="^[A-Za-z]{2,}$" title="Only letters, min 2 characters">
                        <div class="error" id="lnameError">
                        </div>
                    </div>
                </div>

                <div class="row1">
                    <div class="col1">
                        <div class="text">User Name</div>
                        <input type="text" name="user_name" id="uname" required pattern="^[A-Za-z0-9_]{3,}$" title="Letters, numbers, underscore. Min 3 characters">
                        <div class="error" id="unameError">
                        </div>
                    </div>
                    <div class="col1">
                        <div class="text">Phone Number</div>
                        <input type="text" name="phone" id="pnumber" required pattern="^[0-9]{10}$" title="Enter 10-digit number">
                        <div class="error" id="pnumberError">
                        </div>
                    </div>
                </div>

                <div class="row1">
                    <div class="col1">
                        <div class="text">Address</div>
                        <input type="text" name="address" id="address" required>
                        <div class="error" id="addressError">
                            
                        </div>
                    </div>
                    <div class="col1">
                        <div class="text">Email Address</div>
                        <input type="text" name="email" id="email">
                        <div class="error" id="emailError">
                           
                        </div>
                    </div>
                </div>

                <div class="row1">
                    <div class="col1">
                        <div class="text">Password</div>
                        <input type="text" name="password" id="password" required minlength="6" title="Min 6 characters">
                        <div class="error" id="passwordError">
                           
                        </div>
                    </div>
                    <div class="col1">
                        <div class="text">Confirm Password</div>
                        <input type="text" name="pass2" id="pass2" required minlength="6">
                        <div class="error" id="pass2Error">
                        </div>
                    </div>
                </div>
                <input type="submit" value=Register >
            </div>
            </form>
            <p><a href="login.php">Login</a></p>
        </div>
    </div>
    <script>
function validateField(id, regex, message) {
    const input = document.getElementById(id);
    const errorDiv = document.getElementById(id + "Error");
    input.addEventListener("input", () => {
        if (!regex.test(input.value)) {
            errorDiv.textContent = message;
        } else {
            errorDiv.textContent = "";
        }
    });
}

validateField("fname", /^[A-Za-z]{2,}$/, "Only letters, min 2 characters.");
validateField("lname", /^[A-Za-z]{2,}$/, "Only letters, min 2 characters.");
validateField("uname", /^[A-Za-z0-9_]{3,}$/, "Only letters, numbers, and underscores.");
validateField("pnumber", /^[0-9]{10}$/, "Phone must be 10 digits.");
validateField("password", /^.{6,}$/, "Password must be at least 6 characters.");

document.getElementById("pass2").addEventListener("input", function () {
    const pass1 = document.getElementById("password").value;
    const errorDiv = document.getElementById("pass2Error");
    if (this.value !== pass1) {
        errorDiv.textContent = "Passwords do not match.";
    } else {
        errorDiv.textContent = "";
    }
});
</script>

</body>

</html>