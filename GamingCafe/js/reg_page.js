$(document).ready(function () {
    $(".log").on("submit", function (e) {
        e.preventDefault(); // ✅ Prevent page reload

        var formData = {
            first_name: $("#fname").val().trim(),
            last_name: $("#lname").val().trim(),
            user_name: $("#uname").val().trim(),
            phone: $("#pnumber").val().trim(),
            address: $("#address").val().trim(),
            email: $("#email").val().trim(),
            password: $("#password").val().trim(),
            pass2: $("#pass2").val().trim(),
            action: "register" // ✅ Ensure correct action is passed
        };

        console.log("Attempting registration:", formData); // ✅ Debugging

        // ✅ Basic validation
        if (Object.values(formData).some(field => field === "")) {
            alert("All fields are required.");
            return;
        }
        if (formData.password.length < 6) {
            alert("Password must be at least 6 characters.");
            return;
        }
        if (formData.password !== formData.pass2) {
            alert("Passwords do not match.");
            return;
        }

        $.ajax({
            type: "POST",
            url: "crud/user_crud.php?action=register",
            data: formData,
            dataType: "json",
            success: function (response) {
                console.log("Server Response:", response); // ✅ Debugging

                if (response.success) {
                    alert("Registration successful! Redirecting to login...");
                    window.location.href = response.redirect; // ✅ Redirect on success
                } else {
                    alert(response.message); // ✅ Show error message
                }
            },
            error: function (xhr, status, error) {
                console.error("Registration error:", xhr.responseText);
                alert("An error occurred during registration.");
            }
        });
    });
    
});