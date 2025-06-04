$(document).ready(function () {
    $("#loginForm").on("submit", function (e) {
        e.preventDefault(); // ✅ Prevent form reload

        var uname = $("#uname").val().trim();
        var password = $("#password").val().trim();

        console.log("Attempting login:", { uname, password }); // ✅ Debugging

        if (!uname || !password) {
            $("#errorMessage").text("Username and password are required.").show();
            return;
        }

        $.ajax({
            type: "POST",
            url: "crud/user_crud.php?action=login", // ✅ Updated to use action in URL
            data: { uname: uname, password: password },
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging

                if (response.success) {
                    console.log("Redirecting to:", response.redirect);
                    window.location.href = response.redirect; // ✅ Redirect to user.php or admin.php
                } else {
                    console.error("Login failed:", response.message);
                    $("#errorMessage").text(response.message).show(); // ✅ Show error message
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", xhr.responseText);
                $("#errorMessage").text("An error occurred while logging in.").show();
            }
        });
    });
});
