<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Cafe</title>
    <link rel="icon" href="css/img/joystick.png" type="image/icon type">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/home_style.css">
    <link rel="stylesheet" href="mobcss/homestyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- <script src="./js/jqFuwatto.umd.js" defer></script> -->
    <script src="js/home_page.js"></script>
</head>

<body>
    <div class="homearea">
        <div class="upper">
            <nav>
                <div class="logo"><span>G</span>amingCafe</div>
                <div class="navlist">
                    <input type="radio" name="navLinks" value="home" id="navhome" checked><label
                        onclick="$('#navhome').prop('checked', true)" for="navhome"><a href="#">Home</a></label>
                    <input type="radio" name="navLinks" value="services" id="navservices"><label
                        onclick="$('#navservices').prop('checked', true)" for="navservices"><a
                            href="#services">services</a></label>
                    <input type="radio" name="navLinks" value="packages" id="navpackages"><label
                        onclick="$('#navpackages').prop('checked', true)" for="navpackages"><a
                            href="#packages">Packages</a></label>
                    <input type="radio" name="navLinks" value="about" id="navabout"><label
                        onclick="$('#navabout').prop('checked', true)" for="navabout"><a href="#">About</a></label>
                    <input type="radio" name="navLinks" value="contacts" id="navcontacts"><label
                        onclick="$('#navcontacts').prop('checked', true)" for="navcontacts"><a
                            href="#contacts">Contact</a></label>
                </div>
                <div class="rightnav">
                    <button id="regbtn" onclick="bulletBtn();">Register</button>
                    <div class="hline"></div>
                    <button id="logbtn" onclick="loginBtn();">Login</button>
                </div>
            </nav>
        </div>
        <section class="land">
            <div class="wall">
                <div class="upperlayer"></div>
                <div class="wallimg"></div>
                <div class="lineart">
                    <div class="line1"></div>
                </div>
                <div class="container">
                    <div class="subcontainer">
                        <div class="text">
                            <div class="caption">JOIN US.</div>
                            <div class="para">
                            Welcome to the Ultimate Gaming Cafe – Play, Compete, and Conquer! 🎮🔥 Experience top-tier gaming setups, seamless reservations, and exciting challenges. Whether you're here to dominate the leaderboard or just have fun, every session is an adventure!
                            </div>
                            <div class="buttons">
                                <div class="facebook"><a href="https://www.instagram.com/mrsandeepmmv/"><i class="fa-brands fa-instagram"></i> </a></div>
                                <div class="youtube"><i class="fa-brands fa-youtube"></i></div>
                                <div class="contact"><a href="#contacts"><i class="fa-solid fa-phone"></i> Contact</a></div>
                            </div>
                        </div>
                    </div>
                    <div class="subcontainer2">
                        <div class="target" id="target">
                            <div class="circle"></div>
                        </div>
                        <div class="img"></div>
                        <div class="back">
                            <div class="box1 box"></div>
                            <div class="box2 box"></div>
                            <div class="box3 box"></div>
                            <div class="box4 box"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="aboutus">
            <div class="aucontainer">
                <div class="caption">About Us</div>
                <div class="ausubcontainer">
                    <div class="aucon">
                        <div class="slids card">
                            <div class="upperblur"></div>
                            <div class="slid active">
                                <img src="css/img/13.jpg" alt="no Image">
                            </div>
                            <div class="slid">
                                <img src="css/img/12.jpg" alt="no Image">
                            </div>
                            <div class="slid">
                                <img src="css/img/11.jpg" alt="no Image">
                            </div>
                        </div>
                        <div class="dots">
                            <div class="dot active" onclick="changeSlide(0)"></div>
                            <div class="dot" onclick="changeSlide(1)"></div>
                            <div class="dot" onclick="changeSlide(2)"></div>
                        </div>
                    </div>
                    <div class="aucon2">
                    <div class="card">
                    <div class="title">
                       GamingCafe
                    </div>
                    <div class="para">
                        Welcome to <strong>Gaming Cafe</strong> – the ultimate gaming destination! 🎮
                        Experience high-end gaming setups, seamless reservations, and a vibrant gaming community. 
                        Whether you're a casual player or an eSports enthusiast, we provide the perfect environment 
                        for you to play, compete, and connect.
                    </div>
                    </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="dashboard">
            <div class="dashcontainer">
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-desktop"></i></div>
                    <div class="count" id="dashcomputer">10</div>
                    <div class="desc">Computers</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-gamepad"></i></div>
                    <div class="count" id="dashgame">17</div>
                    <div class="desc">Games</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-users"></i></div>
                    <div class="count" id="dashuser">50</div>
                    <div class="desc">Users</div>
                </div>
                <div class="dashcard">
                    <div class="icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="count" id="dashres">150</div>
                    <div class="desc">Reservations</div>
                </div>
            </div>
        </section>
        <section class="service" id="services">
            <div class="caption">
                Our Services
            </div>
            <div class="servicelist">
                <div class="card">
                    <i class="fa-solid fa-headset"></i>
                    <div class="title">
                        Play games
                    </div>
                    <div class="para">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum, dolorem porro ad quam
                        aspernatur ipsam nostrum aliquam impedit nesciunt dolore!
                    </div>
                    <!-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> -->
                </div>
                <div class="card">
                    <i class="fa-regular fa-credit-card"></i>
                    <div class="title">
                        Online Payment
                    </div>
                    <div class="para">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum, dolorem porro ad quam
                        aspernatur ipsam nostrum aliquam impedit nesciunt dolore!
                    </div>
                    <!-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> -->
                </div>
                <div class="card">
                    <i class="fa-solid fa-burger"></i>
                    <div class="title">
                        Foods
                    </div>
                    <div class="para">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum, dolorem porro ad quam
                        aspernatur ipsam nostrum aliquam impedit nesciunt dolore!
                    </div>
                    <!-- <div class="button">
                        <button id="discoverbtn">Discover More</button>
                    </div> -->
                </div>
            </div>
        </section>
        <section class="packages" id="packages">
            <div class="caption">Our Packages</div>
            <div class="packageslist">
                <!-- <div class="packcard">
                    <div class="packprice"><span>Rs</span>150</div>
                    <div class="packname">Pack 1</div>
                    <div class="packdetails"></div>
                </div>
                <div class="packcard">
                    <div class="packname"></div>
                    <div class="packdetails"></div>
                </div>
                <div class="packcard">
                    <div class="packname"></div>
                    <div class="packdetails"></div>
                </div> -->
                <div class="packcard card1">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>100</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">1 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">1 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="packcard card2">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>200</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">2 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">2 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="packcard card3">
                    <div class="pricing">
                        <div class="price">
                            <sup>Rs</sup>
                            <span>300</span>
                        </div>
                        <p>Hourly</p>
                        <span class="type">3 Hour</span>
                    </div>
                    <div class="cardbody">
                        <div class="topshape"></div>
                        <div class="cardcontent">
                            <ul>
                                <li class="point active">3 Hour <i class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">Weekdays & Weekend <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Incress time limit <i class="fa-regular fa-circle-check"></i>
                                </li>
                                <li class="point active">Rs.125 for an extra hour <i
                                        class="fa-regular fa-circle-check"></i></li>
                                <li class="point active">play any game <i class="fa-regular fa-circle-check"></i></li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        <section class="contacts" id="contacts">
            <div class="caption">Contact</div>
            <div class="conoptions">
                <div class="concard">
                    <div class="address conop"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="text">Dget College, Haware City,Thane 400607 </div>
                </div>
                <div class="concard">
                    <div class="num conop"><i class="fa-solid fa-phone"></i></div>
                    <div class="text">+91 9920861483</div>
                </div>
                <div class="concard">
                    <div class="email conop"><i class="fa-solid fa-envelope"></i></div>
                    <div class="text">sandeep124mmv1@gmail.com</div>
                </div>
            </div>
            <div class="condetails">
                <div class="conform">
                    <div class="row">
                        <input type="text" placeholder="Name">
                        <input type="text" placeholder="Email Address">
                    </div>
                    <input type="text" placeholder="Subject">
                    <textarea name="" id="" placeholder="Message"></textarea>
                    <button class="submit">Send Message
                        <!-- <i class="material-icons">send</i> -->
                    </button>
                </div>
                <div class="map">
                    <iframe class="position-relative rounded w-100 h-100"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d31675.07069155449!2d79.99480351508817!3d7.08142232268367!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae2fb67a22e72d9%3A0x913a2c56a49c8d8e!2sGampaha%2C%20Sri%20Lanka!5e0!3m2!1sen!2sbd!4v1664614126869!5m2!1sen!2sbd"
                        frameborder="0" style="width:100%; height:100%; border:0;" allowfullscreen=""
                        aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
        </section>

        <div class="footer">
            <!-- <div class="upperlayer"></div> -->
            <svg xmlns="http://www.w3.org/2000/svg" width="1921.848" height="100" viewBox="0 0 1921.848 100">
                <path id="Path_1" data-name="Path 1"
                    d="M0,75.609s552.531,35.724,844.5,12.234S1127.238,50.651,1459.865,43.8,1921.848-4.158,1921.848-4.158H0Z"
                    transform="translate(0 4.158)" fill="var(--bgcolor)" />
            </svg>
            <div class="row">
                <div class="details">
                    <div class="title">
                        Address
                    </div>
                    Dget College, Haware City,<br>
                    Thane 400607.
                </div>
                <div class="details">
                    <div class="title">
                        About Us
                    </div>
                    <a href="#">Home</a><br>
                    <a href="#services">Services</a><br>
                    <a href="#packages">Packages</a><br>
                    <a href="#aboutus">About</a><br>
                    <a href="#contacts">Contact</a>
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    Play games <br>
                    Online payments <br>
                    Foods
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    Dget College, Haware City,<br>
                    Thane 400607.
                </div>
                <div class="details">
                    <div class="title">
                        Services
                    </div>
                    Play games<br>
                    Online Payment<br>
                    Foods <br>
                </div>
            </div>
            <div class="copyright">
                copyright © 2024 GamingCafe
            </div>

        </div>
    </div>
    <div class="loadercontainer">
        <div class="innercontainer">
            <div class="loader"></div>
        </div>
    </div>
</body>

</html>