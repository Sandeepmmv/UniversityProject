$(document).ready(function () {
    // ✅ Open Payment Modal
    $("#payNowBtn").on("click", function () {
        $("#paymentModal").fadeIn();
    });

    // ✅ Close Payment Modal
    $(".close-btn").on("click", function () {
        $("#paymentModal").fadeOut();
    });

    // ✅ Confirm Payment Action
    $("#confirmPaymentBtn").on("click", function () {
        let cardNumber = $("#cardNumber").val().trim();
        let expiryDate = $("#expiryDate").val().trim();
        let cvv = $("#cvv").val().trim();
        let cardHolder = $("#cardHolder").val().trim();

        if (!cardNumber || !expiryDate || !cvv || !cardHolder) {
            alert("⚠ Please fill all payment details!");
            return;
        }

        // ✅ Mock AJAX Request to Mark Payment as Completed
        $.ajax({
            type: "POST",
            url: "payment_process.php", // ✅ PHP file to update reservation
            data: { action: "complete_payment" },
            dataType: "json",
            success: function (response) {
                if (response.success) {
                    $("#paymentModal").fadeOut();
                    $("#paymentSuccess").fadeIn(); // ✅ Show success message
                } else {
                    alert("⚠ Payment failed! Try again.");
                }
            }
        });
    });
});
