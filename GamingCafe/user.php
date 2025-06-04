<?php
session_start();
$user_id = $_SESSION['user_id'] ?? 0;  // ✅ Get user_id from session
$user_name = $_SESSION['username'] ?? 'Guest'; // ✅ If not found, default to "Guest"
?>

<script>
    var userId = <?php echo json_encode($user_id); ?>;  
    var userName = <?php echo json_encode($user_name); ?>;  

    console.log("User ID from PHP:", userId);
    console.log("User Name from PHP:", userName);
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Cafe</title>
    <link rel="icon" href="css/img/joystick.png" type="image/icon type">
    <link rel="stylesheet" href="css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="stylesheet" href="css/userpanel_style.css">
    <link rel="stylesheet" href="css/pc_style.css">
    <link rel="stylesheet" href="css/pcbook_style.css">
    <link rel="stylesheet" href="css/loader_style.css">
    <link rel="stylesheet" href="css/calendar_style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="js/user_page.js"></script>
    <script src="js/calendar.js"></script>
    <script src="js/reservation_timer.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.3.4/vue.min.js"></script>

</head>

<body>
    <div class="adminarea">
        <div class="container">
            <nav>
                <div class="logo" id="mainlogo"><i class="fa-solid fa-bars"></i>
                    <div class="text"><span>G</span>amingCafe</div>
                </div>
                <div class="hiuser" style="opacity: 0.8;">Hi <span></span></div>
                <div class="logout"><a href="logout.php" id="logoutbtn">Logout</a></div>
            </nav>
            <div class="admin">
                <div class="subcontainer" id="mainsubcontainer">
                    <div class="ul">
                        <input type="radio" name="slidmenu" value="Home" id="menu6" checked>
                        <label for="menu6">
                            <i class="fa-solid fa-house"></i>
                            <div class="text">Home</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Reservations" id="menu1">
                        <label for="menu1">
                            <i class="fa-solid fa-calendar-days"></i>
                            <div class="text">Reservations</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Computers" id="menu2">
                        <label for="menu2">
                            <i class="fa-solid fa-desktop"></i>
                            <div class="text">Computers</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Games" id="menu4">
                        <label for="menu4">
                            <i class="fa-solid fa-gamepad"></i>
                            <div class="text">Games</div>
                        </label>
                        <input type="radio" name="slidmenu" value="Settings" id="menu5">
                        <label for="menu5">
                            <i class="fa-solid fa-gear"></i>
                            <div class="text">Settings</div>
                        </label>
                    </div>
                </div>
                <div class="subcontainer2" id="mainsubcontainer2">
                    <!-- <div class="caption">
                        <div class="text">Reservations</div>
                    </div> -->
                    <div class="homedata">
                        <div class="datatcontainer">
                            <div class="hdsubcontainer">
                                <div class="dashboard" id="userdash">
                                    <div class="cvdec cvdec1"></div>
                                    <div class="cvdec cvdec2"></div>
                                    <div class="chdec chdec1"></div>
                                    <div class="chdec chdec2"></div>
                                    <!-- <div class="part1"></div>
                                    <div class="part1"></div>
                                    <div class="part1"></div> -->
                                    <div class="medleContainer">
                                        <div class="medle" id="dashmedle">
                                        </div>
                                    </div>
                                    <!-- <div class="part2"></div>
                                    <div class="part2"></div>
                                    <div class="part2"></div> -->
                                    <div class="progress">
                                        <div class="memberType"></div>
                                        <div class="progressBar">
                                            <div class="progressBarline">
                                                <div class="progressBarinner"></div>
                                            </div>
                                            <div class="progressNum"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="expbtn">
                                    <div class="text">New Games</div>
                                    <div class="viewmore">View More</div>
                                </div>
                                <div class="gameselector homeselector">
                                    <div class="list ">
                                    </div>
                                </div>
                                <div class="expbtn">
                                    <div class="text">Most Populer Computers</div>
                                    <div class="viewmore">View More</div>
                                </div>
                                <div class="computerselector homeselector">
                                    <div class="list">
                                    </div>
                                </div>
                            </div>
                            <div class="hdsubcontainer2">
                                <div class="eventcalendar">
                                    <div class="datecontainer">
                                        <div class="icon">
                                            <i class="fa-solid fa-calendar-plus"></i>
                                        </div>
                                        <div class="details">
                                            <div class="today">Today</div>
                                            <div class="date">07-06-2022 </div>
                                        </div>
                                    </div>
                                    <div class="events">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="reservationsdata">
                        <div class="datatcontainer">
                            <table id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Computer</th>
                                        <th>Package</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                        <div id="clock" style="display: none;">
                            <p class="rdate"></p>
                            <p class="time"></p>
                            <p class="reserve">Reservation</p>
                        </div>
                         <span id="toggleTimer" style="display: none;">👁️</span> <!-- 👁️ Visible, 🙈 Hidden -->
                    </div>
                    <div class="computersdata">
                        <div class="datatcontainer">
                            <div class="pc">
                                <div class="con">
                                    <div class="pcname" id="computername">PC - 01</div>
                                    <div class="pcdetail">
                                        <div class="pcimg"></div>
                                        <div class="linecon">
                                            <div class="line"></div>
                                        </div>
                                        <div class="pcspec">
                                            <ul id="pcspecul">
                                            </ul>
                                            <div class="booknow"> <button id="booknowbtn">Book Now</button></div>
                                        </div>
                                    </div>
                                    <div class="dec"></div>
                                    <div class="pcselector">
                                        <div class="bluredec"></div>
                                        <div class="pclist">
                                            <div class="dumy"></div>
                                            <div class="dumy"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="con2">
                                    <div class="games">
                                        <div class="gamecaption">Games</div>
                                        <div class="gamelist">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="gamesdata">
                        <div class="datatcontainer">
                            <div class="gameline">
                            </div>
                            <div class="btnline">
                                <div class="innerline">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="settingsdata">
                        <div class="datatcontainer">
                            <div class="stcontainer">
                                <div class="propic"></div>
                                <div class="text">Select a profile picture</div>
                                <div class="propiclist">
                                </div>
                            </div>
                            <div class="stcontainer2">
                                <div class="profiledata">
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">First Name</div>
                                            <input type="text" name="firstname" id="fname">
                                            <div class="error" id="uufname"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Last Name</div>
                                            <input type="text" name="lastname" id="lname">
                                            <div class="error" id="uulname"></div>
                                        </div>
                                    </div>
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">User Name</div>
                                            <input type="text" name="username" id="uname">
                                            <div class="error" id="uuuname"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Phone Number</div>
                                            <input type="text" name="phonenumber" id="pnumber">
                                            <div class="error" id="uupnumber"></div>
                                        </div>
                                    </div>

                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">Address</div>
                                            <input type="text" name="address" id="address">
                                            <div class="error" id="uuaddress"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Email Address</div>
                                            <input type="text" name="email" id="email">
                                            <div class="error" id="uuemail"></div>
                                        </div>
                                    </div>
                                    <button id="updateProFileBtn">Update Profile</button>
                                </div>
                                <div class="text">Change your password</div>
                                <div class="passwordcontainer">
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">Current Password</div>
                                            <input type="text" name="oldpassword" id="oldpassword">
                                            <div class="error" id="uuoldpass"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">New Password</div>
                                            <input type="text" name="newpassword" id="newpassword">
                                            <div class="error" id="uunewpass"></div>
                                        </div>
                                    </div>
                                    <div class="row1">
                                        <div class="col1">
                                            <div class="text">&nbsp;</div>
                                            <button id="changePasswordBtn">Change Password</button>
                                            <div class="error"></div>
                                        </div>
                                        <div class="col1">
                                            <div class="text">Retype New Password</div>
                                            <input type="text" name="confirmPassword" id="confirmPassword">
                                            <div class="error" id="uuconpass"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="pcbookarea">
        <div class="container">
            <div class="close"><button id="closebtn"><i class="fa-solid fa-xmark"></i></button></div>
            <div class="book">
                <div class="capclose">
                    <div class="caption">Book Now</div>
                </div>
                <div class="detail">
                    <div class="dec dec1"></div>
                    <div class="dec dec2"></div>
                    <div class="pc">
                        <div class="pcimg"></div>
                        <div class="pcname">PC - 01</div>
                    </div>
                    <div class="pkg">
                        <div class="exp">Slect a Date<span> *</span></div>
                        <div class="date">
                            <input type="date" name="" id="date">
                        </div>
                        <div class="exp">Slect a pakage<span> *</span></div>
                        <div class="pkglist">
                        </div>
                    </div>
                    <!-- <div class="time">
                        <div class="exp">Slect a time slot<span> *</span></span></div>
                        <div class="timelist">
                        </div>
                        <div class="paynow">
                            <button id="paynowbtn">Book Now</button>
                        </div>
                    </div> -->
                    <div class="time">
    <div class="exp">Select a time slot<span> *</span></div>
    <div class="timelist">
        <!-- Time slots go here -->
    </div>

    <div class="terms">
        <input type="checkbox" id="termsCheck">
        <label for="termsCheck">I agree to the Terms and Conditions</label>
    </div>

    <div class="paynow">
        <button id="paynowbtn" disabled>Book Now</button>
    </div>
</div>


                </div>
            </div>
        </div>
    </div>
    <div class="loadercontainer">
        <div class="innercontainer">
            <div class="loader"></div>
        </div>
    </div>
    <!-- ✅ Payment Modal (Hidden by Default) -->
<!-- <div id="paymentModal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Complete Your Payment</h2>

        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" placeholder="1234 5678 9012 3456">

        <label for="expiryDate">Expiry Date</label>
        <input type="text" id="expiryDate" placeholder="MM/YY">

        <label for="cvv">CVV</label>
        <input type="password" id="cvv" placeholder="123">

        <label for="cardHolder">Cardholder Name</label>
        <input type="text" id="cardHolder" placeholder="John Doe">

        <button id="confirmPaymentBtn">Confirm Payment</button>
    </div>
</div> -->

<!-- ✅ Pay Now Button
<button id="payNowBtn">Pay Now</button> -->

<!-- ✅ Success Message (Hidden Initially) -->
<!-- <div id="paymentSuccess" class="success-message" style="display: none;">
    ✅ Payment Successful! Your reservation is confirmed.
</div> -->

<!-- ✅ Include Scripts -->
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
<!-- <script src="js/payment.js"></script> -->

<script>
    const termsCheckbox = document.getElementById('termsCheck');
    const bookBtn = document.getElementById('paynowbtn');

    termsCheckbox.addEventListener('change', () => {
        if (termsCheckbox.checked) {
            alert(`Terms and Conditions:\n
1. You must show up on time.
2. No refunds after booking.
3. Respect the booking policy.
4. Your data may be stored securely for processing.
5. PAYMENT WILL BE TAKEN AT THE CAFÉ. No online or advance payment.
`);
        }
        bookBtn.disabled = !termsCheckbox.checked;
    });
</script>
    <script>
        var clock = new Vue({
    el: '#clock',
    data: {
        time: '',
        date: ''
    }
});

var week = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
var timerID = setInterval(updateTime, 1000);
updateTime();
function updateTime() {
    var cd = new Date();
    clock.time = zeroPadding(cd.getHours(), 2) + ':' + zeroPadding(cd.getMinutes(), 2) + ':' + zeroPadding(cd.getSeconds(), 2);
    clock.date = zeroPadding(cd.getFullYear(), 4) + '-' + zeroPadding(cd.getMonth()+1, 2) + '-' + zeroPadding(cd.getDate(), 2) + ' ' + week[cd.getDay()];
};

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}
    </script>
</body>

</html>