
var selectedpc;
var gamenextpage;
var gameprevpage;
function createHomePc() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "crud/computers_crud.php?action=view",
        dataType: "json",
        success: function (response) {
            $(".adminarea .homedata .hdsubcontainer .computerselector .list").html("");
            response.forEach(pc => {
                $(".adminarea .homedata .hdsubcontainer .computerselector .list").append(`
                    <div class="gamecard">
                        <img class="gameimg" src="css/img/pc.png">
                        <div class="name">PC - ${pc.cid}</div>
                    </div>
                `);
            });
            
            // Ensure at least 6 placeholders are available
            if (response.length < 6) {
                for (let i = response.length; i < 6; i++) {
                    $(".adminarea .homedata .hdsubcontainer .computerselector .list").append(`
                        <div class="gamecard carddumy"></div>
                    `);
                }
            }
            
            $(".loadercontainer").hide();
        }
    });
}
function cratePc() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "crud/computers_crud.php?action=view",
        dataType: "json",
        success: function (response) {
            $(".adminarea .computersdata .pcselector .pclist").html(""); // Clear previous entries
            
            response.forEach(pc => {
                $(".adminarea .computersdata .pcselector .pclist").append(`
                    <input type="radio" name="computer" value="${pc.cid}" id="pc${pc.cid}" ${(pc.cid == 1) ? 'checked' : ''}>
                    <label for="pc${pc.cid}" id="pcl${pc.cid}" class="pccard">
                        <div class="pccardimg"></div>
                        <div class="text">PC - ${pc.cid}</div>
                    </label>
                `);
            });
            
            // Add dummy elements to maintain layout
            for (let i = 0; i < 2; i++) {
                $(".adminarea .computersdata .pcselector .pclist").append('<div class="dumy"></div>');
            }
            
            // Attach event listener to radio buttons
            $('.adminarea .computersdata .pcselector .pclist input[name="computer"]').on("change", function () {
                displayPcDetails($(this).val());
            });
            
            // Display default PC details
            displayPcDetails(1);
            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching computers:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}
function displayPcDetails(cid) {
    $(".loadercontainer").show();
    selectedpc = cid;
    $.ajax({
        type: "get",
        url: "crud/computers_crud.php?action=viewoneimg",
        data: { "cid": cid },
        dataType: "json",
        success: function (response) {
            if (!response || response.error) {
                console.error("No data found for PC ID:", cid);
                $("#pcspecul").html("<li>No specifications available</li>");
                $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").html("<p>No games available</p>");
                $("#computername").html(`PC - ${cid} (Not Found)`);
                $(".loadercontainer").hide();
                return;
            }

            let pc = response[0];
            $("#pcspecul").html(`
                <li>${pc.spec1 || "N/A"}</li>
                <li>${pc.spec2 || "N/A"}</li>
                <li>${pc.spec3 || "N/A"}</li>
                <li>${pc.spec4 || "N/A"}</li>
                <li>${pc.spec5 || "N/A"}</li>
                <li>${pc.spec6 || "N/A"}</li>
                <li>${pc.spec7 || "N/A"}</li>
            `);
            $("#computername").html(`PC - ${cid}`);
            $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").html("");

            if (pc.games && pc.games.length > 0) {
                pc.games.forEach(game => {
                    $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").append(`
                        <div class="game">
                            <div class="gameimg">
                                <img src="${game.path}" alt="">
                            </div>
                            <div class="gamename">${game.name}</div>
                        </div>
                    `);
                });
            } else {
                $(".adminarea .container .subcontainer2 .computersdata .pc .gamelist").html("<p>No games available</p>");
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching PC details:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}
function crategames(pagenum) {
    $(".loadercontainer").show();

    $.ajax({
        type: "GET",
        url: "crud/games_crud.php?action=partofdata",
        data: { "page": pagenum },
        dataType: "json",
        success: function (response) {
            console.log("✅ Game Data Received:", response); // ✅ Debugging

            if (!response || !response.data || response.data.length === 0) {
                console.warn("⚠ No game data received.");
                $(".adminarea .subcontainer2 .gamesdata .datatcontainer .gameline").html("<p>No games found.</p>");
                return;
            }

            let gameline = $(".adminarea .subcontainer2 .gamesdata .datatcontainer .gameline");
            gameline.html("");

            response.data.forEach(game => {
                gameline.append(`
                    <div class="gamecard">
                        <img loading="lazy" class="gameimg" src="${game.path}" onerror="this.src='default-game.jpg';">
                        <div class="name">${game.name}</div>
                    </div>
                `);
            });

            // ✅ Fill remaining slots with empty placeholders (max 18)
            for (let i = response.data.length; i < 18; i++) {
                gameline.append(`<div class="gamecard carddumy"></div>`);
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("❌ Error Fetching Games:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}
function creatLatestGames() {
    $(".loadercontainer").show();

    $.ajax({
        type: "GET",
        url: "crud/games_crud.php?action=getlatest",
        dataType: "json",
        success: function (response) {
            console.log("✅ Latest Games Data:", response); // ✅ Debugging

            if (!response || !Array.isArray(response) || response.length === 0) {
                console.warn("⚠ No latest games received.");
                $(".adminarea .homedata .hdsubcontainer .gameselector .list").html("<p>No latest games found.</p>");
                return;
            }

            let gamelist = $(".adminarea .homedata .hdsubcontainer .gameselector .list");
            gamelist.html("");

            response.forEach(game => {
                gamelist.append(`
                    <div class="gamecard">
                        <img loading="lazy" class="gameimg" src="${game.path}" onerror="this.src='default-game.jpg';">
                        <div class="name">${game.name}</div>
                    </div>
                `);
            });

            // ✅ Fill remaining slots with empty placeholders (max 6)
            for (let i = response.length; i < 6; i++) {
                gamelist.append(`<div class="gamecard carddumy"></div>`);
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("❌ Error Fetching Latest Games:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

// Fetch all available packages
function createPackage() {
    $(".loadercontainer").show();
    $.ajax({
        type: "get",
        url: "crud/packages_crud.php?action=viewall",
        dataType: "json",
        success: function (response) {
            $(".pcbookarea .detail .pkg .pkglist").html(""); // Clear previous entries
            
            if (response.length === 0) {
                $(".pcbookarea .detail .pkg .pkglist").html("<p>No packages available</p>");
                $(".loadercontainer").hide();
                return;
            }

            response.forEach((pkg, index) => {
                let pkgtime = pkg.package_time;
                $(".pcbookarea .detail .pkg .pkglist").append(`
                    <input type="radio" name="mainpkg" value="${pkg.package_id}" id="pkg${pkg.package_id}" ${index === 0 ? "checked" : ""}>
                    <label for="pkg${pkg.package_id}" class="pkgt">
                        <div class="pkgname">${pkg.package_name}</div>
                        <div class="details">${pkgtime} ${(pkgtime == 1) ? "hour" : "hours"} - Rs${pkg.package_price}</div>
                    </label>
                `);
            });

            // ✅ Trigger `getResPkgData` immediately for the first package
            let firstPkg = response[0].package_id;
            let selectedDate = $(".pcbookarea .pkg #date").val();
            let selectedPc = $("input[name='computer']:checked").val();

            if (selectedDate && selectedPc) {
                getResPkgData(firstPkg, selectedDate, selectedPc);
            }

            // ✅ Attach event listener for package selection
            $('.pcbookarea .detail .pkg .pkglist input[name="mainpkg"]').on("change", function () {
                let selectedPkg = $(this).val();
                let selectedDate = $(".pcbookarea .pkg #date").val();
                let selectedPc = $("input[name='computer']:checked").val();
                
                if (selectedPkg && selectedDate && selectedPc) {
                    getResPkgData(selectedPkg, selectedDate, selectedPc);
                }
            });

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching packages:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

// function createPackage() {
//     $(".loadercontainer").show();
//     $.ajax({
//         type: "get",
//         url: "crud/packages_crud.php?action=viewall",
//         dataType: "json",
//         success: function (response) {
//             $(".pcbookarea .detail .pkg .pkglist").html(""); // Clear previous entries
            
//             response.forEach(pkg => {
//                 let pkgtime = pkg.package_time;
//                 $(".pcbookarea .detail .pkg .pkglist").append(`
//                     <input type="radio" name="mainpkg" value="${pkg.package_id}" id="pkg${pkg.package_id}">
//                     <label for="pkg${pkg.package_id}" class="pkgt">
//                         <div class="pkgname">${pkg.package_name}</div>
//                         <div class="details">${pkgtime} ${(pkgtime == 1) ? "hour" : "hours"} - Rs${pkg.package_price}</div>
//                     </label>
//                 `);
//             });
            
//             // Attach event listener for package selection
//             $('.pcbookarea .detail .pkg .pkglist input[name="mainpkg"]').on("change", function () {
//                 let selectedPkg = $(this).val();
//                 let selectedDate = $(".pcbookarea .pkg #date").val();
//                 let selectedPc = $("input[name='computer']:checked").val();
                
//                 if (selectedPkg && selectedDate && selectedPc) {
//                     getResPkgData(selectedPkg, selectedDate, selectedPc);
//                 }
//             });
            
//             $(".loadercontainer").hide();
//         },
//         error: function (xhr, status, error) {
//             console.error("Error fetching packages:", xhr.responseText);
//             $(".loadercontainer").hide();
//         }
//     });
// }
function getResPkgData(pkg, date, pcid) {
    $(".loadercontainer").show();
    console.log("Fetching package data:", { pkg, date, pcid });
    
    $.ajax({
        type: "get",
        url: "crud/reservations_crud.php?action=respkgdata",
        data: { "packid": pkg, "date": date, "pcid": pcid },
        dataType: "json",
        success: function (response) {
            console.log("API Response:", response);

            if (!response || typeof response !== "object") {
                console.error("Invalid API response format", response);
                $(".loadercontainer").hide();
                return;
            }

            if (!response["package"] || response["package"].length === 0) {
                console.warn("No package data found.");
                $(".pcbookarea .container .detail .time .timelist").html("<p>No available time slots for this package.</p>");
                $(".loadercontainer").hide();
                return;
            }

            let pkgtime = response["package"][0]?.package_time || 1;  // Default to 1 hour if missing
            let availableTimes = response["availableTimes"] || [];
            let isFullDayAvailable = response["isFullDayAvailable"] || false;

            // ✅ Ensure availableTimes is an array
            if (!Array.isArray(availableTimes)) {
                console.warn("availableTimes is not an array", availableTimes);
                availableTimes = [];
            }

            // ✅ If no available times, show message
            if (availableTimes.length === 0 && !isFullDayAvailable) {
                $(".pcbookarea .container .detail .time .timelist").html("<p>No available time slots.</p>");
            } else {
                createTimeSolts(pkgtime, availableTimes, isFullDayAvailable);
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching package data:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

// function getResPkgData(pkg, date, pcid) {
//     $(".loadercontainer").show();
//     console.log("Fetching package data:", pkg, date, pcid);
    
//     $.ajax({
//         type: "get",
//         url: "crud/reservations_crud.php?action=respkgdata",
//         data: { "packid": pkg, "date": date, "pcid": pcid },
//         dataType: "json",
//         success: function (response) {
//             if (response["package"] && response["package"].length > 0) {
//                 let pkgtime = response["package"][0].package_time;
//                 let availableTimes = response["availableTimes"] || [];
//                 let isFullDayAvailable = response["isFullDayAvailable"] || false;
//                 console.log("Response received:", response);
//                 createTimeSolts(pkgtime, availableTimes, isFullDayAvailable);
//             } else {
//                 console.error("Invalid package data received", response);
//             }
//             $(".loadercontainer").hide();
//         },
//         error: function (xhr, status, error) {
//             console.error("Error fetching package data:", xhr.responseText);
//             $(".loadercontainer").hide();
//         }
//     });
// }
function createTimeSolts(pkgTime, availableTimes, isFullDayAvailable) {
    $(".pcbookarea .container .detail .time .timelist").html("");

    if (!isFullDayAvailable) {
        // ✅ If full-day is NOT available, use provided available times
        availableTimes.forEach(element => {
            let startTime = element;
            let endTime = element + pkgTime;

            if (endTime > 20) return;  // ✅ Prevent overflow beyond 8 PM

            $(".pcbookarea .container .detail .time .timelist").append(`
                <input type="radio" name="timeslot" value="${startTime}" id="tsid${startTime}">
                <label for="tsid${startTime}" class="timecard">${formatTime(startTime)} - ${formatTime(endTime)}</label>
            `);
        });
    } else {
        // ✅ If full-day is available, generate time slots from 8 AM to 8 PM
        for (let i = 8; i < 20; i++) {
            let startTime = i;
            let endTime = i + pkgTime;

            if (endTime > 20) break;  // ✅ Stop if the time slot exceeds 8 PM

            $(".pcbookarea .container .detail .time .timelist").append(`
                <input type="radio" name="timeslot" value="${startTime}" id="tsid${startTime}">
                <label for="tsid${startTime}" class="timecard">${formatTime(startTime)} - ${formatTime(endTime)}</label>
            `);
        }
    }
}

// ✅ Correct 24-hour format to 12-hour AM/PM format
function formatTime(hour) {
    if (hour === 0) return "12:00 AM";  // Midnight case
    if (hour === 12) return "12:00 PM"; // Noon case

    let suffix = hour >= 12 ? "PM" : "AM";
    let formattedHour = hour % 12 || 12; // Convert 0 to 12 (for 12 AM)

    return `${formattedHour}:00 ${suffix}`;
}


// function createTimeSolts(pkgTime, availableTimes, isFullDayAvailable) {
//     $(".pcbookarea .container .detail .time .timelist").html("");
//     if (!isFullDayAvailable) {
//         availableTimes.forEach(element => {
//             $(".pcbookarea .container .detail .time .timelist").append(`
//                 <input type="radio" name="timeslot" value="${element}" id="tsid${element}">
//                 <label for="tsid${element}" class="timecard">${element}:00 - ${element + pkgTime}:00</label>
//              `);
//         });
//     } else {
//         for (let i = 8; i < 20; i++) {
//             if (i + pkgTime > 20) {
//                 break;
//             }
//             $(".pcbookarea .container .detail .time .timelist").append(`
//                 <input type="radio" name="timeslot" value="${i}" id="tsid${i}">
//                 <label for="tsid${i}" class="timecard">${i}:00 - ${i + pkgTime}:00</label>
//         `);
//         }
//     }
// }
function setEventCalender() {
    if (!userId || userId == 0) {
        console.error("User ID is missing.");
        return;
    }

    $.ajax({
        type: "get",
        url: "crud/reservations_crud.php?action=geteventdetails",
        data: { user_id: userId },  // ✅ Send user ID
        dataType: "json",
        success: function (response) {
            if (!response || response.error) {
                console.error("Error:", response.error);
                return;
            }

            let resv = response.reservation;
            $(".adminarea .homedata .datatcontainer .eventcalendar .events").html("");

            // Display registered event
            if (resv.length < 10 && response.regdate) {
                let regdate = response.regdate.split(" ")[0];  // ✅ Fix: Format regdate correctly
                let regtime = response.regdate.split(" ")[1] ?? "Unknown Time";

                $(".adminarea .homedata .datatcontainer .eventcalendar .events").append(`
                    <div class="eventcard">
                        <div class="eventstatus">
                            <div class="dot" id="eventreg"></div>
                        </div>
                        <div class="eventdetails">
                            <div class="eventname">Registered</div>
                            <div class="eventtime">${regdate} - ${regtime}</div>
                        </div>
                    </div>
                `);
            }

            // Loop through reservations and display them
            resv.forEach(event => {
                $(".adminarea .homedata .datatcontainer .eventcalendar .events").append(`
                    <div class="eventcard">
                        <div class="eventstatus">
                            <div class="dot" id="event${event.id}"></div>
                        </div>
                        <div class="eventdetails">
                            <div class="eventname">Reservation</div>
                            <div class="eventtime">${event.date} / ${event.time} - PC ${event.computer_id}</div>
                        </div>
                    </div>
                `);

                // Highlight future reservations
                var today = new Date().toISOString().split("T")[0]; 
                var resday = event.date;

                if (resday > today) {
                    $(`#event${event.id}`).css("background-color", "green");
                }
            });

            // Auto-scroll to latest event
            var d = $(".adminarea .homedata .datatcontainer .eventcalendar .events");
            d.scrollTop(d.prop("scrollHeight"));
        },
        error: function (xhr, status, error) {
            console.error("Error fetching event data:", xhr.responseText);
        }
    });
}

function createProPics() {
    $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").html("");
    for (var i = 1; i < 22; i++) {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").append(`
            <input type="radio" name="propic" value="${i}" id="pp${i}">
            <label for="pp${i}" id="ppl${i}" class="propiccard">
                <img loading="lazy" src="css/img/propics/${i}.jpg"> 
            </label>       
        `);
    }
    setUserData();
}
function setUserData() {
    $(".loadercontainer").show();

    $.ajax({
        type: "GET",
        url: "crud/user_crud.php?action=viewone",
        dataType: "json",
        success: function (response) {
            if (!response || response.length === 0) {
                console.error("No user data found.");
                $(".loadercontainer").hide();
                return;
            }

            let user = response[0]; // ✅ Ensure correct data structure
            console.log("User Data:", user);

            // ✅ Update user profile safely
            $("#fname").val(user.first_name || "");
            $("#lname").val(user.last_name || "");
            $("#uname").val(user.user_name || "");
            $("#pnumber").val(user.phone_number || "");
            $("#address").val(user.address || "");
            $("#email").val(user.email || "");

            // ✅ Update profile picture safely
            let profilePic = user.propic ? `url("css/img/propics/${user.propic}.jpg")` : `url("css/img/propics/default.jpg")`;
            $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", profilePic);
            $("#dashmedle").css("background-image", profilePic);

            // ✅ Check and mark profile picture selection
            if (user.propic) {
                $(`#pp${user.propic}`).prop("checked", true);
            }

            // ✅ Calculate Progress & Rank
            let reservations = user.reservations || 0;
            let progressPercentage = Math.min(100, (reservations / 100) * 100); // ✅ Cap progress at 100%
            let userRank = "New Member"; // Default rank
            let medalClass = "default-medal"; // Default style

            if (reservations >= 50) {
                userRank = "Gold Member 🥇";
                medalClass = "gold-medal";
            } else if (reservations >= 30) {
                userRank = "Silver Member 🥈";
                medalClass = "silver-medal";
            } else if (reservations >= 10) {
                userRank = "Bronze Member 🥉";
                medalClass = "bronze-medal";
            }

            // ✅ Update Progress Bar & Rank Display
            $(".progressBarinner").css("width", `${progressPercentage}%`);
            $(".progressNum").html(`${reservations} `);
            $(".memberType").html(`${user.user_name} <span>${userRank}</span>`);
            $("#dashmedle").attr("class", `medle ${medalClass}`); // ✅ Update medal UI

            // ✅ Set hiuser display name
            $(".adminarea .hiuser span").html(user.user_name || "User");

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching user data:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

// ✅ Add Click Event to Show/Hide Progress Bar
$("#dashmedle").on("click", function () {
    $(".progress").toggle();
});

$(document).ready(function () {
    // current date
    let today = new Date();
    let formattedDate = today.toLocaleDateString("en-GB"); // Format: DD-MM-YYYY
    $(".details .date").text(formattedDate);
    createProPics();
    createHomePc();
    setEventCalender();
    creatLatestGames();
    cratePc();
    crategames();
    createPackage();
    $(".logo").on("click", function () {
        $("#mainsubcontainer").toggleClass("subcontainerwidth");
        $("#mainsubcontainer .ul .text").toggle(10);
        $("#mainlogo i").toggle(50);
        $("#mainlogo .text").toggle(50);
    
        if (!$("#mainsubcontainer").hasClass("subcontainerwidth")) {
            $("#mainlogo").animate({ width: "4%" }, 100);
            $("#mainsubcontainer").animate({ width: "4%" }, 100);
            $("#mainsubcontainer2").animate({ width: "96%" }, 100);
        } else {
            $("#mainlogo").animate({ width: "11.1%" }, 100);
            $("#mainsubcontainer").animate({ width: "12%" }, 100);
            $("#mainsubcontainer #").animate({ width: "88%" }, 100);  // ✅ FIXED
        }
    });
    $('.subcontainer input[name="slidmenu"]').on("change", function () {
        var caption = $(this).val();
        //$(".subcontainer2 .caption").html(caption);
        $(".subcontainer2 .homedata").hide();
        $(".subcontainer2 .reservationsdata").hide();
        $(".subcontainer2 .computersdata").hide();
        $(".subcontainer2 .packagesdata").hide();
        $(".subcontainer2 .gamesdata").hide();
        $(".subcontainer2 .settingsdata").hide();
        $(`.subcontainer2 .${caption.toString().toLowerCase()}data`).show();
        if (caption.toString().toLowerCase() == "home") {
            $(".adminarea .container nav .hiuser").show();
        } else {
            $(".adminarea .container nav .hiuser").hide();
        }
    });
    $(".settingsdata input[type='radio']").on("change", function () {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("css/img/propics/${$(this).val()}.jpg")`);
    });
    $(".computersdata .con2 .exp").on("click", function () {
        $(".computersdata .con2 .pcgames").toggleClass("maxpanel");
        if ($(".computersdata .con2 .pcgames").hasClass("maxpanel")) {
            $(".computersdata .con2 .pcgames").animate({ height: "0%" });
            $(".computersdata .con2 .pcspecs").animate({ height: "85%" });
            // $(this.lastChild).removeClass("fa-angle-down").addClass("fa-angle-up");

        } else {
            $(".computersdata .con2 .pcgames").animate({ height: "85%" });
            $(".computersdata .con2 .pcspecs").animate({ height: "0%" });
        }
    });
    $("#booknowbtn").on("click", function () {
        $(".computersdata .pc").hide();
        $(".pcbookarea").show();
        $(".pcbookarea .container .detail .pc .pcname").html(`pc - ${$("input[name='computer']:checked").val()}`);
    });
    $("#closebtn").on("click", function () {
        $(".computersdata .pc").show();
        $(".pcbookarea").hide();
    });
    $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]').on("change", function () {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("css/img/propics/${$(this).val()}.jpg")`);
    });
    $("#updateProFileBtn").on("click", function () {
        $(".loadercontainer").show();
        var fname = $("#fname").val();
        var lname = $("#lname").val();
        var uname = $("#uname").val();
        var pnumber = $("#pnumber").val();
        var address = $("#address").val();
        var email = $("#email").val();
        var propic = $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]:checked').val();
        $.ajax({
            type: "post",
            url: "crud/user_crud.php?action=update",
            data: {
                "firstname": fname,
                "lastname": lname,
                "username": uname,
                "phonenumber": pnumber,
                "address": address,
                "email": email,
                "propic": propic,
            },
            dataType: "json",
            success: function (response) {
                if (!response.success) {
                    let mg = response.message;
                    $("#uufname").html("firstname" in mg ? mg.firstname[0] : "");
                    $("#uulname").html("lastname" in mg ? mg.lastname[0] : "");
                    $("#uuuname").html("username" in mg ? mg.username[0] : "");
                    $("#uupnumber").html("phonenumber" in mg ? mg.phonenumber[0] : "");
                    $("#uuaddress").html("address" in mg ? mg.address[0] : "");
                    $("#uuemail").html("email" in mg ? mg.email[0] : "");
                } else {
                    $(".adminarea .subcontainer2 .settingsdata .profiledata .error").html("");
                }
                $(".loadercontainer").hide();
            }
        });
    });
    $("#changePasswordBtn").on("click", function () {
        $(".loadercontainer").show();
    
        $.ajax({
            type: "POST",
            url: "crud/user_crud.php?action=updatep", // ✅ Fixed URL
            data: {
                "oldpassword": $("#oldpassword").val(),
                "newpassword": $("#newpassword").val(),
                "newpassword_confirmation": $("#confirmPassword").val()
            },
            dataType: "json",
            success: function (response) {
                console.log("Response:", response); // ✅ Debugging
    
                if (!response.success) {
                    let mg = response.message;
                    $("#uuoldpass").html(mg.oldpassword ? mg.oldpassword[0] : "");
                    $("#uunewpass").html(mg.newpassword ? mg.newpassword[0] : "");
                    $("#uuconpass").html(mg.newpassword_confirmation ? mg.newpassword_confirmation[0] : "");
                } else {
                    $(".adminarea .subcontainer2 .settingsdata .passwordcontainer .error").html("Password updated successfully!");
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
    
    $("#dashmedle").on("click", function () {

    });
    //payment
    // $("#paynowbtn").on("click", function () {
    //     $(".loadercontainer").show();
    
    //     var date = $(".pcbookarea #date").val();
    //     var packid = $(".pcbookarea .pkg input[name='mainpkg']:checked").val();
    //     var pcid = $("input[name='computer']:checked").val();
    //     var start_time = $("input[name='timeslot']:checked").val();
    //     var time = $("input[name='timeslot']:checked + label").html();
    //     var amount = $("#packagePrice").text().trim() || "0"; // ✅ Ensure amount is valid
    
    //     // ✅ Debugging - Check values before redirection
    //     console.log("Redirecting to payment:", { amount, userId, userName, date, time, pcid, packid, start_time });
    
    //     if (!userId || !userName || !date || !packid || !pcid || !start_time || !time || amount === "0") {
    //         alert("⚠ Missing required fields. Please fill all details.");
    //         $(".loadercontainer").hide();
    //         return;
    //     }
    
    //     // ✅ Redirect to payment page (Use `pay.php` instead of `pay.html`)
    //     window.location.href = `payment/pay.php?amount=${encodeURIComponent(amount)}&user_id=${userId}&user_name=${encodeURIComponent(userName)}&date=${date}&time=${time}&pcid=${pcid}&packid=${packid}&start_time=${start_time}`;
    // });
    
    
    //previous
    // $("#paynowbtn").on("click", function () {
    //     $(".loadercontainer").show();
    
    //     // ✅ Using userId and userName from PHP
    //     console.log("User ID:", userId);
    //     console.log("User Name:", userName);
    
    //     var date = $(".pcbookarea #date").val();
    //     var packid = $(".pcbookarea .pkg input[name='mainpkg']:checked").val();
    //     var pcid = $("input[name='computer']:checked").val();
    //     var start_time = $("input[name='timeslot']:checked").val();
    //     var time = $("input[name='timeslot']:checked + label").html();
    
    //     if (!userId || !userName || !date || !packid || !pcid || !start_time || !time) {
    //         alert("Please select all required fields before proceeding.");
    //         $(".loadercontainer").hide();
    //         return;
    //     }
    //     console.log("Sending Data to Server:", { 
    //         "user_id": userId,  
    //         "user_name": userName,  
    //         "date": date, 
    //         "time": time, 
    //         "computer_id": pcid, 
    //         "package_id": packid, 
    //         "start_time": start_time 
    //     });
        
    
    //     $.ajax({
    //         type: "POST",
    //         url: "crud/reservations_crud.php?action=store",
    //         data: { 
    //             "user_id": userId,  
    //             "user_name": userName,  
    //             "date": date, 
    //             "time": time, 
    //             "computer_id": pcid, 
    //             "package_id": packid, 
    //             "start_time": start_time 
    //         },
    //         dataType: "json",
    //         success: function (response) {
    //             console.log("✅ Server Response:", response);
    //             if (response.success) {
    //                 alert("Reservation confirmed!");
    //             } else {
    //                 alert("❌ Error: " + (response.error || "Could not complete reservation."));
    //             }
    //             $(".loadercontainer").hide();
    //         },
    //         error: function (xhr, status, error) {
    //             console.error("❌ AJAX Error:", xhr.responseText);
    //             alert("An error occurred while booking. Please try again.");
    //             $(".loadercontainer").hide();
    //         }
    //     });
    // });
    $("#paynowbtn").on("click", function () {
        $(".loadercontainer").show();
    
        var date = $("#date").val();
        var packid = $("input[name='mainpkg']:checked").val(); // Debug package selection
        var pcid = $("input[name='computer']:checked").val();
        var start_time = $("input[name='timeslot']:checked").val();
        var time = $("input[name='timeslot']:checked + label").html();
    
        console.log("Selected Package ID:", packid);
    
        if (!packid) {
            alert("Please select a package before proceeding.");
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/reservations_crud.php?action=store",
            data: { 
                "user_id": userId,  
                "user_name": userName,  
                "date": date, 
                "time": time, 
                "computer_id": pcid, 
                "package_id": packid, 
                "start_time": start_time 
            },
            dataType: "json",
            success: function (response) {
                console.log("✅ Server Response:", response);
                if (response.success) {
                    alert("Reservation confirmed!");
                } else {
                    alert("❌ Error: " + (response.error || "Could not complete reservation."));
                }
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", xhr.responseText);
                alert("An error occurred while booking. Please try again.");
                $(".loadercontainer").hide();
            }
        });
    });
    
    var requestUrl = "crud/reservations_crud.php?action=userdata"; // ✅ Correct URL with action parameter
    console.log("Fetching DataTable data from:", requestUrl); // ✅ Debugging

    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: requestUrl, // ✅ Use action-based request
            type: "GET",
            dataType: "json",
            error: function (xhr, error, thrown) {
                console.error("DataTable AJAX Error:", xhr.responseText);
            }
        },
        columns: [
            { data: 'user_id', name: 'user_id' },
            { data: 'user_name', name: 'user_name' },
            { data: 'date', name: 'date' },
            { data: 'time', name: 'time' },
            { data: 'computer_id', name: 'computer_id' },
            { data: 'package_id', name: 'package_id' }
        ]
    });
});
