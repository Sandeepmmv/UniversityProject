document.addEventListener("DOMContentLoaded", function () {
    console.log("✅ Payment script loaded!");

    // ✅ Extract parameters from URL
    const urlParams = new URLSearchParams(window.location.search);
    const amount = urlParams.get("amount") || "0";
    const userId = urlParams.get("user_id") || "";
    const userName = urlParams.get("user_name") || "";
    const date = urlParams.get("date") || "";
    const time = urlParams.get("time") || "";
    const pcid = urlParams.get("pcid") || "";
    const packid = urlParams.get("packid") || "";
    const start_time = urlParams.get("start_time") || "";

    console.log("✅ Extracted Data:", { amount, userId, userName, date, time, pcid, packid, start_time });

    // ✅ Update the payment form
    document.getElementById("amount").value = amount;
    document.getElementById("summary-amount").textContent = `₹${amount}`;
    document.getElementById("summary-taxes").textContent = `₹${(amount * 0.18).toFixed(2)}`; // 18% Tax
    document.getElementById("summary-total").textContent = `₹${(amount * 1.18).toFixed(2)}`;

    // ✅ Payment Submission
    document.getElementById("payment-form").addEventListener("submit", async function (e) {
        e.preventDefault();

        const paymentMethod = document.getElementById("payment-method").value;
        if (!paymentMethod) {
            alert("⚠ Please select a payment method.");
            return;
        }

        const formData = {
            user_id: userId,
            user_name: userName,
            amount: parseFloat(amount),
            payment_method: paymentMethod,
        };

        document.getElementById("payment-status").textContent = "Processing...";

        try {
            const response = await fetch("/GamingCafe/crud/payment_crud.php?action=process", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(formData),
            });

            const result = await response.json();
            document.getElementById("payment-status").textContent = result.message;
            document.getElementById("payment-status").classList.add(result.status === "success" ? "success" : "error");

            if (result.status === "success") {
                alert("✅ Payment successful! Booking reservation...");

                // ✅ Auto-book reservation after successful payment
                fetch("/GamingCafe/crud/reservations_crud.php?action=store", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({
                        user_id: userId,
                        user_name: userName,
                        date: date,
                        time: time,
                        computer_id: pcid,
                        package_id: packid,
                        start_time: start_time,
                        transaction_id: result.transaction_id, // ✅ Save transaction ID
                        payment_method: paymentMethod,
                    }),
                })
                    .then(res => res.json())
                    .then(reservation => {
                        if (reservation.success) {
                            alert("✅ Reservation confirmed!");
                            window.location.href = "/GamingCafe/reservation_success.html"; // ✅ Redirect after booking
                        } else {
                            alert("❌ Reservation failed: " + reservation.error);
                        }
                    });
            } else {
                alert("❌ Payment failed. Try again.");
            }
        } catch (error) {
            console.error("❌ Network error:", error);
            alert("⚠ Network error. Please try again.");
        }
    });
});
