$(document).ready(function () {
    console.log("Reservation Timer Script Loaded!"); // ✅ Debugging

    $.ajax({
        type: "GET",
        url: "crud/reservation_check.php",
        dataType: "json",
        success: function (response) {
            console.log("Response received:", response); // ✅ Debugging

            // ✅ If no active reservation, hide everything
            if (!response.success || !response.start_time || !response.duration) {
                console.warn("No active reservations detected.");
                $("#clock").hide();
                $("#toggleTimer").hide();
                $(".reservationsdata").removeClass("timer-active"); // ✅ Remove blur
                return;
            }

            // ✅ Extract reservation details
            let startTime = response.start_time; // Expected: "HH:MM"
            let duration = parseInt(response.duration) * 60 * 60; // Convert hours to seconds
            let pcNumber = response.pc_number || "Unknown"; // ✅ Get PC number

            // ✅ Validate time
            if (!startTime || isNaN(duration)) {
                console.error("Invalid time data received:", response);
                return;
            }

            // ✅ Convert start time to timestamp
            let [startHour, startMinute] = startTime.split(":").map(Number);
            let startTimestamp = new Date();
            startTimestamp.setHours(startHour, startMinute, 0, 0);

            let endTimestamp = new Date(startTimestamp.getTime() + duration * 1000);

            // ✅ Ensure clock is visible
            $("#clock").show();
            $("#toggleTimer").show();
            $(".reservationsdata").addClass("timer-active"); // ✅ Blur only reservationsdata

            // ✅ Set initial text
            $(".rdate").text(new Date().toLocaleDateString("en-GB"));
            $(".reserve").html(`Reservation on PC - ${pcNumber}`);
            console.log("Timer is now visible!"); // ✅ Debugging

            // ✅ Update timer every second
            let interval = setInterval(function () {
                let now = new Date();
                let remainingTime = Math.floor((endTimestamp - now) / 1000);

                if (remainingTime <= 0) {
                    clearInterval(interval);
                    $(".reserve").text("Reservation Expired!");
                    alert("⚠ Your reservation time has ended!");
                    return;
                }

                let hours = Math.floor(remainingTime / 3600);
                let minutes = Math.floor((remainingTime % 3600) / 60);
                let seconds = remainingTime % 60;

                console.log(`Time Left: ${hours}:${minutes}:${seconds}`); // ✅ Debugging

                $(".time").text(
                    `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`
                );

                // ✅ Show warning when time is low
                if (remainingTime < 300) {
                    $(".reserve").text("⚠ Time is running out on PC - " + pcNumber);
                    $(".reserve").css("color", "red");
                }
            }, 1000);
        },
        error: function (xhr, status, error) {
            console.error("AJAX Error:", xhr.responseText);
        }
    });

    // ✅ Toggle Timer Visibility
    $("#toggleTimer").on("click", function () {
        if ($("#clock").is(":visible")) {
            $("#clock").hide();
            $(".reservationsdata").removeClass("timer-active"); // ✅ Remove blur
            $(this).text("👁️"); // Show eye icon when hidden
        } else {
            $("#clock").show();
            $(".reservationsdata").addClass("timer-active"); // ✅ Blur only reservationsdata
            $(this).text("❌"); // Hide eye icon when visible
        }
    });
});
