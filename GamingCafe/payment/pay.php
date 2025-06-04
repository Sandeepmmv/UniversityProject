<?php
// ✅ Include Database Connection
include '../config.php';

// ✅ Extract URL Parameters
$user_id = $_GET['user_id'] ?? '';
$user_name = $_GET['user_name'] ?? '';
$date = $_GET['date'] ?? '';
$time = $_GET['time'] ?? '';
$pcid = $_GET['pcid'] ?? '';
$packid = $_GET['packid'] ?? '';
$start_time = $_GET['start_time'] ?? '';

// ✅ Fetch Package Price from Database (Using `package_price` column)
$amount = 0;
if (!empty($packid)) {
    $stmt = $conn->prepare("SELECT package_price FROM packages WHERE id = ?");
    $stmt->bind_param("i", $packid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $amount = $row['package_price']; // ✅ Correct column name
    }
    $stmt->close();
}

// ✅ Redirect if no valid amount is found
if ($amount <= 0) {
    echo "<script>alert('⚠ Invalid package ID or price. Returning to booking.'); window.location.href = '/GamingCafe/index.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>💳 PaySimple - Payment Gateway</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="payment-app">
        <header>
            <div class="logo">💳 PaySimple</div>
        </header>

        <main>
            <div class="payment-container">
                <div class="payment-form">
                    <h2>Complete Your Payment</h2>
                    <form id="payment-form">
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" id="amount" value="<?php echo $amount; ?>" readonly />
                        </div>

                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" id="name" value="<?php echo htmlspecialchars($user_name); ?>" readonly />
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" id="email" placeholder="Your email" required />
                        </div>

                        <div class="form-group">
                            <label>Payment Method</label>
                            <select id="payment-method" required>
                                <option value="">Select Payment Method</option>
                                <option value="credit_card">Credit Card</option>
                                <option value="debit_card">Debit Card</option>
                                <option value="upi">UPI</option>
                                <option value="net_banking">Net Banking</option>
                            </select>
                        </div>

                        <button type="submit" class="pay-button">Pay Now</button>
                    </form>

                    <div id="payment-status" class="payment-status"></div>
                </div>

                <div class="payment-summary">
                    <h3>Payment Summary</h3>
                    <div class="summary-details">
                        <div class="summary-row">
                            <span>Amount</span>
                            <span id="summary-amount">₹<?php echo $amount; ?></span>
                        </div>
                        <div class="summary-row">
                            <span>Taxes</span>
                            <span id="summary-taxes">₹<?php echo number_format($amount * 0.18, 2); ?></span>
                        </div>
                        <div class="summary-row total">
                            <span>Total</span>
                            <span id="summary-total">₹<?php echo number_format($amount * 1.18, 2); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer>
            <div class="footer-content">
                <p>© 2024 PaySimple</p>
                <div class="footer-links">
                    <a href="#">Security</a>
                    <a href="#">Support</a>
                </div>
            </div>
        </footer>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        console.log("✅ Payment script loaded!");

        document.getElementById("payment-form").addEventListener("submit", async function (e) {
            e.preventDefault();

            const paymentMethod = document.getElementById("payment-method").value;
            if (!paymentMethod) {
                alert("⚠ Please select a payment method.");
                return;
            }

            const formData = {
                user_id: "<?php echo $user_id; ?>",
                user_name: "<?php echo $user_name; ?>",
                amount: <?php echo $amount; ?>,
                payment_method: paymentMethod
            };

            document.getElementById("payment-status").textContent = "Processing...";

            try {
                const response = await fetch("crud/payment_crud.php?action=process", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify(formData),
                });

                const result = await response.json();
                document.getElementById("payment-status").textContent = result.message;
                document.getElementById("payment-status").classList.add(result.status === "success" ? "success" : "error");

                if (result.status === "success") {
                    alert("✅ Payment successful! Booking reservation...");

                    fetch("crud/reservations_crud.php?action=store", {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({
                            user_id: "<?php echo $user_id; ?>",
                            user_name: "<?php echo $user_name; ?>",
                            date: "<?php echo $date; ?>",
                            time: "<?php echo $time; ?>",
                            computer_id: "<?php echo $pcid; ?>",
                            package_id: "<?php echo $packid; ?>",
                            start_time: "<?php echo $start_time; ?>",
                            transaction_id: result.transaction_id, 
                            payment_method: paymentMethod,
                        }),
                    })
                    .then(res => res.json())
                    .then(reservation => {
                        if (reservation.success) {
                            alert("✅ Reservation confirmed!");
                            window.location.href = "/GamingCafe/reservation_success.php";
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
    </script>
</body>
</html>
