var selectedpc;
var gameid;
var gamearray = [];

function createPc() {
    $(".loadercontainer").show();

    $.ajax({
        type: "GET",
        url: "crud/computers_crud.php?action=view",
        dataType: "json",
        success: function (response) {
            $(".adminarea .subcontainer2 .computersdata .con .pcline").empty(); // Clear list

            response.forEach(computer => {
                $(".adminarea .subcontainer2 .computersdata .con .pcline").append(`
                    <input type="radio" name="computer" value="${computer.cid}" id="pc${computer.cid}">
                    <label for="pc${computer.cid}" id="pcl${computer.cid}" class="pc">${computer.cid}</label>
                `);
            });

            $('.subcontainer2 .computersdata .pcline input[name="computer"]').on("change", function () {
                displayPcDetails($(this).val()); // Show PC details when selected
            });

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching computers:", error);
            $(".loadercontainer").hide();
        }
    });
}


function displayPcDetails(cid) {
    $(".loadercontainer").show();
    selectedpc = cid;
    console.log("Selected PC:", selectedpc);

    $.ajax({
        type: "GET",
        url: "crud/computers_crud.php?action=viewone",
        data: { "cid": cid },
        dataType: "json",
        success: function (response) {
            if (response.length === 0) {
                console.warn("No data found for PC ID:", cid);
                $(".loadercontainer").hide();
                return;
            }

            let pc = response[0];

            $("#pcspec1").val(pc.spec1 || "");
            $("#pcspec2").val(pc.spec2 || "");
            $("#pcspec3").val(pc.spec3 || "");
            $("#pcspec4").val(pc.spec4 || "");
            $("#pcspec5").val(pc.spec5 || "");
            $("#pcspec6").val(pc.spec6 || "");
            $("#pcspec7").val(pc.spec7 || "");
            $("#computername").html(`PC - ${cid}`);

            gamearray = [];
            $("input:checkbox[name='pcgame']").prop("checked", false);

            if (pc.games && Array.isArray(pc.games)) {
                pc.games.forEach(game => {
                    $(`#pcgame${game.id}`).prop("checked", true);
                    gamearray.push(game.id);
                });
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("Error fetching PC details:", error);
            $(".loadercontainer").hide();
        }
    });
}

function creategame() {
    console.log("crategame() function started!"); // ✅ Debugging

    $(".loadercontainer").show(); // Show loading animation

    $.ajax({
        type: "GET",
        url: "crud/games_crud.php?action=view", // ✅ Ensure this path is correct
        dataType: "json", // Expect JSON response
        success: function (response) {
            console.log("Game Response Received:", response); // ✅ Debugging

            // Check if response is an array
            if (!Array.isArray(response)) {
                console.error("Invalid response format:", response);
                $(".loadercontainer").hide();
                return;
            }

            // ✅ Clear existing game checkboxes
            let gameContainer = $(".adminarea .subcontainer2 .computersdata .con2 .pcgames");
            gameContainer.empty();

            // ✅ Append checkboxes dynamically
            response.forEach(game => {
                console.log(`Adding game: ${game.name}`); // ✅ Debug each game
                gameContainer.append(`
                    <input type="checkbox" name="pcgame" value="${game.id}" id="pcgame${game.id}">
                    <label for="pcgame${game.id}" id="pcgamel${game.id}">${game.name}</label>
                `);
            });

            $(".loadercontainer").hide(); // Hide loading animation
        },
        error: function (xhr, status, error) {
            console.error("Error fetching games:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}




function checkPack() {
    $(document).on("change", '.adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name="mainpkg"]', function () {
        $(".loadercontainer").show();
        
        let selectedPackId = $(this).val();
        console.log("Selected Package ID:", selectedPackId); // ✅ Debugging

        $.ajax({
            type: "GET",
            url: "crud/packages_crud.php?action=viewone", // ✅ Fixed URL
            data: { "packid": selectedPackId },
            dataType: "json",
            success: function (response) {
                console.log("Package Details Received:", response); // ✅ Debugging

                if (!Array.isArray(response) || response.length === 0) {
                    console.warn("No package data found!");
                    $("#packname, #packtime, #packprice").val("");
                    $(".loadercontainer").hide();
                    return;
                }

                let pack = response[0]; // ✅ Get package details
                $("#packname").val(pack.package_name);
                $("#packtime").val(pack.package_time);
                $("#packprice").val(pack.package_price);

                $(".loadercontainer").hide(); // ✅ Hide loader
            },
            error: function (xhr, status, error) {
                console.error("Error fetching package details:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
}

function createPackage() {
    console.log("createPackage() function started!"); // ✅ Debugging

    $(".loadercontainer").show(); // Show loader

    $.ajax({
        type: "GET",
        url: "crud/packages_crud.php?action=viewall", // ✅ Fixed URL
        dataType: "json",
        success: function (response) {
            console.log("Package Response Received:", response); // ✅ Debugging

            // ✅ Validate response format
            if (!Array.isArray(response) || response.length === 0) {
                console.error("Invalid package data received:", response);
                $(".loadercontainer").hide();
                return;
            }

            let packageList = $(".adminarea .container .admin .subcontainer2 .packagesdata .con .pkg .pkglist");
            packageList.empty(); // ✅ Clear previous packages

            // ✅ Append new package options
            response.forEach(pkg => {
                let pkgtime = pkg.package_time;
                let packageHtml = `
                    <input type="radio" name="mainpkg" value="${pkg.package_id}" id="pkg${pkg.package_id}">
                    <label for="pkg${pkg.package_id}" class="pkgt">
                        <div class="pkgname">${pkg.package_name}</div>
                        <div class="details">${pkgtime} ${(pkgtime == 1) ? "hour" : "hours"} - Rs${pkg.package_price}</div>
                    </label>`;
                
                packageList.append(packageHtml);
            });

            console.log("All packages added. Now running checkPack()..."); // ✅ Debugging
            checkPack(); // ✅ Ensure package selection works

            $(".loadercontainer").hide(); // Hide loader
        },
        error: function (xhr, status, error) {
            console.error("Error fetching package data:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

function createProPics() {
    $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").html("");
    for (var i = 1; i < 22; i++) {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propiclist").append(`
            <input type="radio" name="propic" value="${i}" id="pp${i}">
            <label for="pp${i}" id="ppl${i}" class="propiccard">
                <img src="css/img/propics/${i}.jpg"> 
            </label>       
        `);
    }
    setUserData();
}
function setUserData() {
    console.log("setUserData() function started!"); // ✅ Debugging

    $(".loadercontainer").show(); // Show loader

    $.ajax({
        type: "GET",
        url: "crud/user_crud.php?action=viewone", // ✅ Corrected URL
        dataType: "json", // ✅ Ensure JSON format
        success: function (response) {
            console.log("User Data Received:", response); // ✅ Debugging

            // ✅ Validate response format
            if (!Array.isArray(response) || response.length === 0) {
                console.error("Invalid user data received:", response);
                $(".loadercontainer").hide();
                return;
            }

            let user = response[0]; // ✅ Get first user object

            // ✅ Fill user form fields
            $("#fname").val(user.first_name || "");
            $("#lname").val(user.last_name || "");
            $("#uname").val(user.user_name || "");
            $("#pnumber").val(user.phone_number || "");
            $("#address").val(user.address || "");
            $("#email").val(user.email || "");

            // ✅ Update profile picture dynamically
            let profileImageUrl = `css/img/propics/${user.propic}.jpg`;
            $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("${profileImageUrl}")`);
            console.log("Profile Image Set:", profileImageUrl); // ✅ Debugging

            // ✅ Select the correct profile picture radio button
            $(`input[name='profile_pic']`).prop("checked", false); // Uncheck all
            $(`#pp${user.propic}`).prop("checked", true);
            console.log(`Selected Profile Picture ID: pp${user.propic}`); // ✅ Debugging

            $(".loadercontainer").hide(); // Hide loader
        },
        error: function (xhr, status, error) {
            console.error("Error fetching user data:", xhr.responseText);
            $(".loadercontainer").hide();
        }
    });
}

$(document).ready(function () {
    createPc();
    creategame();
    createPackage();
    createProPics();
    $(".logo").on("click", function () {
        $(".subcontainer").toggleClass("subcontainerwidth");
        $(".subcontainer .ul .text").toggle(10);
        $(".logo i").toggle(50);
        $(".logo .text").toggle(50);
        if (!$(".subcontainer").hasClass("subcontainerwidth")) {
            $(".logo").animate({ width: "4%" }, 100);
            $(".subcontainer").animate({ width: "4%" }, 100);
            $(".subcontainer2").animate({ width: "96%" }, 100);
        } else {
            $(".logo").animate({ width: "12%" }, 100);
            $(".subcontainer").animate({ width: "12%" }, 100);
            $(".subcontainer2").animate({ width: "88%" }, 100);
        };
    });

    $('.subcontainer input[name="slidmenu"]').on("change", function () {
        var caption = $(this).val();
        $(".subcontainer2 .caption").html(caption);
        $(".subcontainer2 .reservationsdata").hide();
        $(".subcontainer2 .usersdata").hide();
        $(".subcontainer2 .computersdata").hide();
        $(".subcontainer2 .packagesdata").hide();
        $(".subcontainer2 .gamesdata").hide();
        $(".subcontainer2 .settingsdata").hide();
        $(`.subcontainer2 .${caption.toString().toLowerCase()}data`).show();
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
    $("#newgamebtn").on("click", function () {
        console.log("New game button clicked!"); // ✅ Debugging
    
        $(".loadercontainer").show();
    
        let gameName = $("#gamename").val().trim();
        let gameUrl = $("#gameurl").val().trim();
    
        // ✅ Validate empty fields before sending AJAX
        if (!gameName || !gameUrl) {
            console.warn("Validation failed: Fields cannot be empty.");
            $("#gamenameerror").html(gameName ? "" : "Game name is required.");
            $("#gameurlerror").html(gameUrl ? "" : "Game image link is required.");
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/games_crud.php?action=store", // ✅ Corrected URL
            data: { "name": gameName, "imagelink": gameUrl },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging
    
                if (!response.success) {
                    let mg = response.message || {};
                    $("#gamenameerror").html(mg.name ? mg.name[0] : "");
                    $("#gameurlerror").html(mg.imagelink ? mg.imagelink[0] : "");
                    $(".loadercontainer").hide();
                    return;
                }
    
                alert("Game added successfully!");
                $("#gamename, #gameurl").val(""); // ✅ Clear input fields
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error storing game:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
    $("#updatepcbtn").on("click", function () {
        $(".loadercontainer").show();
        var gamearraytemp = [];
        $("input:checkbox[name='pcgame']:checked").each(function () {
            gamearraytemp.push(parseInt($(this).val()));
        });
        var newgamearray = gamearraytemp.filter(function (val) {
            return gamearray.indexOf(val) == -1;
        });
        var delgamearray = gamearray.filter(item => gamearraytemp.indexOf(item) == -1);
        gamearray.push(...newgamearray);

        $.ajax({
            type: "post",
            url: "/computer/update",
            data: {
                "cid": $('.subcontainer2 .computersdata .pcline input[name="computer"]:checked').val(),
                "spec1": $("#pcspec1").val(),
                "spec2": $("#pcspec2").val(),
                "spec3": $("#pcspec3").val(),
                "spec4": $("#pcspec4").val(),
                "spec5": $("#pcspec5").val(),
                "spec6": $("#pcspec6").val(),
                "spec7": $("#pcspec7").val(),
                "games": JSON.stringify(newgamearray),
                "delgames": JSON.stringify(delgamearray),
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                if (!response.success) {
                    var mg = response.message;
                    for (var i = 1; i < 8; i++) {
                        if (`spec${i}` in mg) {
                            $(`#pcspec${i}`).css("border", "1px solid rgba(150, 0, 0, 100)");
                        } else {
                            $(`#pcspec${i}`).css("border", "none");
                        }
                    }
                }
                $(".loadercontainer").hide();
            }
        });
    });
    $("#newpcbtn").on("click", function () {
        $(".loadercontainer").show();
        
        var newgamearray = [];
        $("input:checkbox[name='pcgame']:checked").each(function () {
            newgamearray.push($(this).val());
        });
    
        // ✅ Validate that at least one game is selected
        if (newgamearray.length === 0) {
            alert("Please select at least one game for the PC.");
            $(".loadercontainer").hide();
            return;
        }
    
        let pcSpecs = {};
        let isValid = true;
    
        // ✅ Validate all PC spec inputs
        for (let i = 1; i <= 7; i++) {
            let specValue = $(`#pcspec${i}`).val().trim();
            if (!specValue) {
                $(`#pcspec${i}`).css("border", "2px solid red");
                isValid = false;
            } else {
                $(`#pcspec${i}`).css("border", "none");
                pcSpecs[`spec${i}`] = specValue;
            }
        }
    
        // ✅ Stop if any spec is missing
        if (!isValid) {
            alert("All PC specifications must be filled.");
            $(".loadercontainer").hide();
            return;
        }
    
        console.log("Submitting new PC:", pcSpecs, "Games:", newgamearray);
    
        $.ajax({
            type: "POST",
            url: "crud/computers_crud.php?action=store", // ✅ Ensure this file exists
            data: {
                ...pcSpecs,
                games: JSON.stringify(newgamearray), // Convert games array to JSON
            },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response);
    
                if (response.success) {
                    $(".adminarea .subcontainer2 .computersdata .con .pcline").append(`
                        <input type="radio" name="computer" value="${response.cid}" id="pc${response.cid}">
                        <label for="pc${response.cid}" id="pcl${response.cid}" class="pc">${response.cid}</label>
                    `);
    
                    $('.subcontainer2 .computersdata .pcline input[name="computer"]').on("change", function () {
                        displayPcDetails($(this).val());
                    });
    
                    alert("PC added successfully!");
                } else {
                    alert(response.message || "An error occurred while adding the PC.");
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error adding PC:", xhr.responseText);
                alert("Failed to add PC. Please try again.");
                $(".loadercontainer").hide();
            }
        });
    });
    
    $("#deletepcbtn").on("click", function () {
        if (!selectedpc) {
            alert("No computer selected for deletion!");
            return;
        }
    
        $(".loadercontainer").show();
        console.log("Deleting computer ID:", selectedpc);
    
        $.ajax({
            type: "GET",
            url: "crud/computer_crud.php?action=delete", // ✅ Use correct URL format
            data: { "cid": selectedpc },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    console.log("Deleted Computer ID:", response.cid);
    
                    // ✅ Remove elements correctly
                    $(`#pc${response.cid}, #pcl${response.cid}`).remove();
                } else {
                    alert("Failed to delete computer: " + response.message);
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting computer:", xhr.responseText);
                alert("⚠ Error deleting computer. Please try again.");
                $(".loadercontainer").hide();
            }
        });
    });
    
    
   

    
    

    $("#delpackbtn").on("click", function () {
        $(".loadercontainer").show();
    
        var packid = $(".adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name='mainpkg']:checked").val();
    
        console.log("Attempting to delete package with ID:", packid); // ✅ Debugging
    
        // ✅ Ensure a package is selected before attempting to delete
        if (!packid) {
            alert("Please select a package before deleting.");
            $(".loadercontainer").hide();
            return;
        }
    
        // ✅ Confirmation before deleting
        if (!confirm("Are you sure you want to delete this package?")) {
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/packages_crud.php?action=delete", // ✅ Fixed URL
            data: { "packid": packid },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging
    
                if (response.success) {
                    $(`.pkglist #pkg${packid}`).remove(); // ✅ Remove radio button
                    $(`.pkglist label[for=pkg${packid}]`).remove(); // ✅ Remove label
                    alert("Package deleted successfully!");
                } else {
                    alert("Error: " + response.message);
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error deleting package:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
    
    $("#updatepackbtn").on("click", function () {
        $(".loadercontainer").show();
    
        var packid = $(".adminarea .subcontainer2 .packagesdata .pkg .pkglist input[name='mainpkg']:checked").val();
        var packname = $("#packname").val().trim();
        var packtime = $("#packtime").val().trim();
        var packprice = $("#packprice").val().trim();
    
        console.log("Attempting to update package:", { packid, packname, packtime, packprice }); // ✅ Debugging
    
        // ✅ Client-side validation before sending AJAX request
        if (!packid) {
            console.warn("Validation failed: No package selected.");
            alert("Please select a package to update.");
            $(".loadercontainer").hide();
            return;
        }
        if (!packname || !packtime || !packprice) {
            console.warn("Validation failed: Fields cannot be empty.");
            $("#packnameerror").html(!packname ? "Package name is required." : "");
            $("#packtimeerror").html(!packtime ? "Package time is required." : "");
            $("#packpriceerror").html(!packprice ? "Package price is required." : "");
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/packages_crud.php?action=update", // ✅ Fixed URL
            data: { packid, packname, packtime, packprice },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging
    
                if (response.success) {
                    // ✅ Update UI
                    $(`.pkglist label[for=pkg${packid}] .pkgname`).html(packname);
                    $(`.pkglist label[for=pkg${packid}] .details`).html(`${packtime} ${(packtime == 1) ? "hour" : "hours"} - Rs${packprice}`);
    
                    alert("Package updated successfully!");
                } else {
                    console.warn("Validation errors received from server:", response.message);
                    let mg = response.message || {};
                    $("#packnameerror").html(mg.packname ? mg.packname[0] : "");
                    $("#packtimeerror").html(mg.packtime ? mg.packtime[0] : "");
                    $("#packpriceerror").html(mg.packprice ? mg.packprice[0] : "");
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error updating package:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
    
    $("#newpackbtn").on("click", function () {
        $(".loadercontainer").show();
    
        var packname = $("#packname").val().trim();
        var packtime = $("#packtime").val().trim();
        var packprice = $("#packprice").val().trim();
    
        console.log("Attempting to store package:", { packname, packtime, packprice }); // ✅ Debugging
    
        // ✅ Client-side validation before sending AJAX request
        if (!packname || !packtime || !packprice) {
            console.warn("Validation failed: Fields cannot be empty.");
            $("#packnameerror").html(!packname ? "Package name is required." : "");
            $("#packtimeerror").html(!packtime ? "Package time is required." : "");
            $("#packpriceerror").html(!packprice ? "Package price is required." : "");
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/packages_crud.php?action=store", // ✅ Fixed URL
            data: { packname, packtime, packprice },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging
    
                if (response.success) {
                    let pkgtime = response.package_time;
                    
                    $(".adminarea .container .admin .subcontainer2 .packagesdata .con .pkg .pkglist").append(`
                        <input type="radio" name="mainpkg" value="${response.package_id}" id="pkg${response.package_id}">
                        <label for="pkg${response.package_id}" class="pkgt">
                            <div class="pkgname">${response.package_name}</div>
                            <div class="details">${pkgtime} ${(pkgtime == 1) ? "hour" : "hours"} - Rs${response.package_price}</div>
                        </label>
                    `);
    
                    // ✅ Reset form fields after successful addition
                    $("#packname, #packtime, #packprice").val("");
                } else {
                    console.warn("Validation errors received from server:", response.message);
                    let mg = response.message || {};
                    $("#packnameerror").html(mg.packname ? mg.packname[0] : "");
                    $("#packtimeerror").html(mg.packtime ? mg.packtime[0] : "");
                    $("#packpriceerror").html(mg.packprice ? mg.packprice[0] : "");
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("Error storing package:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
    
    $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]').on("change", function () {
        $(".adminarea .subcontainer2 .settingsdata .stcontainer .propic").css("background-image", `url("css/img/propics/${$(this).val()}.jpg")`);
    });
    $("#updateProFileBtn").on("click", function () {
    $(".loadercontainer").show();

    var fname = $("#fname").val().trim();
    var lname = $("#lname").val().trim();
    var uname = $("#uname").val().trim();
    var pnumber = $("#pnumber").val().trim();
    var address = $("#address").val().trim();
    var email = $("#email").val().trim();
    var propic = $('.adminarea .subcontainer2 .settingsdata .propiclist input[type="radio"]:checked').val() || "";

    console.log("Updating profile with:", { fname, lname, uname, pnumber, address, email, propic });

    // ✅ Client-side validation
    if (!fname || !lname || !uname || !pnumber || !address || !email) {
        alert("All fields are required.");
        $(".loadercontainer").hide();
        return;
    }

    $.ajax({
        type: "POST",
        url: "crud/user_crud.php?action=update", // ✅ Ensure correct endpoint
        data: {
            firstname: fname,
            lastname: lname,
            username: uname,
            phonenumber: pnumber,
            address: address,
            email: email,
            propic: propic,
        },
        dataType: "json",
        success: function (response) {
            console.log("Server Response:", response);

            if (response.success) {
                alert("✅ Profile updated successfully!");
                $(".adminarea .subcontainer2 .settingsdata .profiledata .error").html(""); // ✅ Clear previous errors
            } else {
                let mg = response.message || {};
                $("#uufname").html(mg.firstname ? mg.firstname[0] : "");
                $("#uulname").html(mg.lastname ? mg.lastname[0] : "");
                $("#uuuname").html(mg.username ? mg.username[0] : "");
                $("#uupnumber").html(mg.phonenumber ? mg.phonenumber[0] : "");
                $("#uuaddress").html(mg.address ? mg.address[0] : "");
                $("#uuemail").html(mg.email ? mg.email[0] : "");
            }

            $(".loadercontainer").hide();
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
            alert("❌ Failed to update profile. Please try again.");
            $(".loadercontainer").hide();
        }
    });
});

    $("#changePasswordBtn").on("click", function () {
        $(".loadercontainer").show();
    
        var oldpassword = $("#oldpassword").val().trim();
        var newpassword = $("#newpassword").val().trim();
        var confirmPassword = $("#confirmPassword").val().trim();
    
        console.log("Attempting to change password:", { oldpassword, newpassword, confirmPassword }); // ✅ Debugging
    
        // ✅ Client-side validation before sending AJAX request
        if (!oldpassword || !newpassword || !confirmPassword) {
            alert("All password fields are required.");
            $(".loadercontainer").hide();
            return;
        }
        if (newpassword.length < 6) {
            alert("New password must be at least 6 characters.");
            $(".loadercontainer").hide();
            return;
        }
        if (newpassword !== confirmPassword) {
            alert("New passwords do not match.");
            $(".loadercontainer").hide();
            return;
        }
    
        $.ajax({
            type: "POST",
            url: "crud/user_crud.php?action=updatep", // ✅ Fixed URL
            data: { 
                oldpassword: oldpassword, 
                newpassword: newpassword, 
                newpassword_confirmation: confirmPassword // ✅ Fixed key name
            },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging
    
                if (response.success) {
                    alert("✅ Password updated successfully!");
                    $("#oldpassword, #newpassword, #confirmPassword").val(""); // ✅ Clear fields
                } else {
                    console.warn("Validation errors:", response.message);
                    let mg = response.message || {};
                    $("#uuoldpass").html(mg.oldpassword ? mg.oldpassword[0] : "");
                    $("#uunewpass").html(mg.newpassword ? mg.newpassword[0] : "");
                    $("#uuconpass").html(mg.newpassword_confirmation ? mg.newpassword_confirmation[0] : "");
                }
    
                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                alert("❌ Failed to update password. Please try again.");
                $(".loadercontainer").hide();
            }
        });
    });
    
    var dataTable = $('#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "crud/reservations_crud.php?action=anydata", // ✅ Correct URL
            type: "GET",
            dataType: "json",
            error: function (xhr, error, thrown) {
                console.error("Error fetching reservation data:", xhr.responseText); // ✅ Debugging
            }
        },
        columns: [
            { data: "user_id", name: "user_id" },
            { data: "user_name", name: "user_name" },
            { data: "date", name: "date" },
            { data: "time", name: "time" },
            { data: "computer_id", name: "computer_id" },
            { data: "package_id", name: "package_id" }
        ]
    });
    var userTable = $('#userTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "crud/user_crud.php?action=anydata", // ✅ Correct URL
            type: "GET",
            dataType: "json",
            error: function (xhr, error, thrown) {
                console.error("Error fetching user data:", xhr.responseText); // ✅ Debugging
            }
        },
        columns: [
            { data: "id", name: "id" },
            { data: "first_name", name: "first_name" },
            { data: "last_name", name: "last_name" },
            { data: "user_name", name: "user_name" },
            { data: "phone_number", name: "phone_number" },
            { data: "address", name: "address" },
            { data: "email", name: "email" }
        ]
    });

    if (!$.fn.DataTable) {
        console.error("DataTables library is missing!");
        return;
    }

    console.log("Initializing game DataTable..."); // ✅ Debugging

});
$(document).ready(function () {
    console.log("Initializing Game DataTable...");

    var table = $("#gamedataTable").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "crud/games_crud.php?action=anydata", // ✅ Ensure correct URL
            type: "GET",
            dataType: "json",
            error: function (xhr, error, thrown) {
                console.error("❌ Error fetching game data:", xhr.responseText);
            }
        },
        columns: [
            { data: "id", name: "id" },
            { data: "name", name: "name" }
        ],
        responsive: true, // ✅ Makes table responsive
        language: {
            emptyTable: "No games available" // ✅ Custom message if no data
        }
    });

    let selectedGameId = null; // Store selected game ID

    // ✅ Row Selection - Highlight row & Get game details
    $("#gameurl").on("keypress", function (e) {
        if (e.which == 13) { // ✅ Use `e.which` instead of `keyCode`
            e.preventDefault(); // ✅ Prevent default form submission if inside a form
    
            let imageUrl = $("#gameurl").val().trim();
    
            // ✅ Prevent empty input from setting the image
            if (!imageUrl) {
                alert("⚠ Please enter a valid image URL.");
                return;
            }
    
            $(".loadercontainer").show();
    
            // ✅ Check if the image exists before updating the background
            let img = new Image();
            img.onload = function () {
                $(".adminarea .admin .subcontainer2 .gamesdata .con2 .img").css("background-image", `url("${imageUrl}")`);
                $(".loadercontainer").hide();
            };
            img.onerror = function () {
                alert("⚠ Invalid image URL. Please enter a valid link.");
                $(".loadercontainer").hide();
            };
    
            img.src = imageUrl; // ✅ Load the image to check if it's valid
        }
    });
    
    $("#gamedataTable tbody").on("click", "tr", function () {
        $("#gamedataTable tbody tr").removeClass("selected"); // Remove previous selection
        $(this).addClass("selected"); // Highlight new selection

        let rowData = table.row(this).data(); // ✅ Use DataTables API
        if (!rowData) {
            console.warn("⚠ No data found for selected row.");
            return;
        }

        selectedGameId = rowData.id;
        $("#gamename").val(rowData.name);

        console.log("Selected Game ID:", selectedGameId);

        // ✅ Fetch game details (image link) from the server
        $.ajax({
            type: "GET",
            url: "crud/games_crud.php?action=viewone",
            data: { id: selectedGameId },
            dataType: "json",
            success: function (response) {
                console.log("Game details response:", response);
        
                // ✅ Ensure response is valid and contains data
                if (!response || response.length === 0 || !response[0].path) {
                    console.warn("⚠ No game image found.");
                    $("#gameurl").val(""); // Clear input if no image
                    $(".adminarea .admin .subcontainer2 .gamesdata .con2 .img").css("background-image", "none");
                    return;
                }
        
                let imageUrl = response[0].path.trim(); // ✅ Access the correct path
                $(".adminarea .admin .subcontainer2 .gamesdata .con2 .img").css("background-image", `url("${imageUrl}")`);
                $("#gameurl").val(imageUrl);
            },
            error: function (xhr, status, error) {
                console.error("❌ Error fetching game details:", xhr.responseText);
            }
        });
        
    });

    // ✅ Update Game Function
    $("#updategamebtn").on("click", function () {
        $(".loadercontainer").show();

        let gamename = $("#gamename").val().trim();
        let gameurl = $("#gameurl").val().trim();

        console.log("Attempting to update game:", { selectedGameId, gamename, gameurl });

        // ✅ Validate input fields
        if (!selectedGameId) {
            alert("⚠ No game selected. Please select a game to update.");
            $(".loadercontainer").hide();
            return;
        }
        if (!gamename || !gameurl) {
            alert("⚠ Game name and image URL are required.");
            $(".loadercontainer").hide();
            return;
        }

        $.ajax({
            type: "POST",
            url: "crud/games_crud.php?action=update",
            data: { gameid: selectedGameId, name: gamename, imagelink: gameurl },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response);

                if (response.success) {
                    alert("✅ Game updated successfully!");
                    table.ajax.reload(null, false); // ✅ Refresh DataTable
                } else {
                    alert("❌ Failed to update game. " + response.message);
                }

                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", xhr.responseText);
                alert("⚠ An error occurred. Please try again.");
                $(".loadercontainer").hide();
            }
        });
    });

    // ✅ Create New Game
    $("#newgamebtn").on("click", function () {
        $(".loadercontainer").show();

        let gamename = $("#gamename").val().trim();
        let gameurl = $("#gameurl").val().trim();

        if (!gamename || !gameurl) {
            alert("⚠ Game name and image URL are required.");
            $(".loadercontainer").hide();
            return;
        }

        $.ajax({
            type: "POST",
            url: "crud/games_crud.php?action=store",
            data: { name: gamename, imagelink: gameurl },
            dataType: "json",
            success: function (response) {
                console.log("New Game Response:", response);

                if (response.success) {
                    alert("✅ Game added successfully!");
                    table.ajax.reload(null, false); // ✅ Refresh DataTable
                } else {
                    alert("❌ Failed to add game. " + response.message);
                }

                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });

    // ✅ Delete Game
    $("#delgamebtn").on("click", function () {
        if (!selectedGameId) {
            alert("⚠ No game selected. Please select a game to delete.");
            return;
        }

        if (!confirm("Are you sure you want to delete this game?")) return;

        $(".loadercontainer").show();

        $.ajax({
            type: "POST",
            url: "crud/games_crud.php?action=delete",
            data: { id: selectedGameId },
            dataType: "json",
            success: function (response) {
                console.log("Delete Response:", response);

                if (response.success) {
                    alert("✅ Game deleted successfully!");
                    table.ajax.reload(null, false); // ✅ Refresh DataTable
                    $("#gamename, #gameurl").val(""); // ✅ Clear inputs
                    $(".img").css("background-image", "none");
                } else {
                    alert("❌ Failed to delete game. " + response.message);
                }

                $(".loadercontainer").hide();
            },
            error: function (xhr, status, error) {
                console.error("❌ AJAX Error:", xhr.responseText);
                $(".loadercontainer").hide();
            }
        });
    });
});
