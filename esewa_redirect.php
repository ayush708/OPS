<?php
// Configuration
$secretKey = "8gBm/:&EnhH.1/q("; // Replace with your actual secret key

// Function to generate the HMAC SHA-256 signature
function generateSignature($secretKey, $total_amount, $transaction_uuid, $product_code) {
    $data = "{$total_amount},{$transaction_uuid},{$product_code}";
    return base64_encode(hash_hmac('sha256', $data, $secretKey, true));
}

// Function to verify the HMAC SHA-256 signature
function verifySignature($secretKey, $data, $providedSignature) {
    $generatedSignature = base64_encode(hash_hmac('sha256', $data, $secretKey, true));
    return $providedSignature === $generatedSignature;
}

// Collect transaction details
$total_amount = $_POST['total_amount'] ?? '';
$transaction_uuid = $_POST['transaction_uuid'] ?? '';
$product_code = $_POST['product_code'] ?? '';

// Generate the signature
$signature = generateSignature($secretKey, $total_amount, $transaction_uuid, $product_code);

// HTML form for redirecting to eSewa
?>

<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to eSewa</title>
</head>
<body>
    <form id="esewa-form" action="https://epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" name="amount" value="100" required>
        <input type="hidden" name="tax_amount" value="10" required>
        <input type="hidden" name="total_amount" value="<?php echo htmlspecialchars($total_amount); ?>" required>
        <input type="hidden" name="transaction_uuid" value="<?php echo htmlspecialchars($transaction_uuid); ?>" required>
        <input type="hidden" name="product_code" value="<?php echo htmlspecialchars($product_code); ?>" required>
        <input type="hidden" name="product_service_charge" value="0" required>
        <input type="hidden" name="product_delivery_charge" value="0" required>
        <input type="hidden" name="success_url" value="https://yourwebsite.com/esewa_success.php" required>
        <input type="hidden" name="failure_url" value="https://yourwebsite.com/esewa_failure.php" required>
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required>
        <input type="hidden" name="signature" value="<?php echo htmlspecialchars($signature); ?>" required>
        <input type="submit" value="Pay with eSewa">
    </form>

    <script>
        // Automatically submit the form
        document.getElementById('esewa-form').submit();
    </script>
</body>
</html>

<?php
// Handling eSewa response
if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Make sure eSewa uses POST
    $response = $_POST; // eSewa usually uses POST for response

    // Extract parameters from the response
    $transaction_code = $response['transaction_code'] ?? '';
    $status = $response['status'] ?? '';
    $total_amount = $response['total_amount'] ?? '';
    $transaction_uuid = $response['transaction_uuid'] ?? '';
    $product_code = $response['product_code'] ?? '';
    $signed_field_names = $response['signed_field_names'] ?? '';
    $providedSignature = $response['signature'] ?? '';

    // Generate data string to verify the signature
    $data = "{$total_amount},{$transaction_uuid},{$product_code}";

    if (verifySignature($secretKey, $data, $providedSignature)) {
        if ($status === 'COMPLETE') {
            // Handle successful payment
            echo "Payment successful. Transaction ID: " . htmlspecialchars($transaction_code);
        } else {
            // Handle other statuses
            echo "Payment status: " . htmlspecialchars($status);
        }
    } else {
        // Signature mismatch
        echo "Signature verification failed.";
    }
} else {
    echo "Invalid request method.";
}
?>
