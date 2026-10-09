<?php
/**
 * ========================================================================
 * PAYHERO STK PUSH CALLBACK HANDLER — SHARED RELAY
 * ========================================================================
 *
 * DEPLOYMENT INSTRUCTIONS:
 * 1. Upload this file (payhero_callback.php) to your public web server's
 *    root directory (e.g., public_html in cPanel).
 * 2. It must be accessible via a public HTTPS URL. Example:
 *    https://samuelbuilds.top/payhero_callback.php
 * 3. Log into your PayHero dashboard, go to Settings or Channel Configuration,
 *    and enter the URL above as your "Callback URL".
 *
 * IMPORTANT ARCHITECTURE NOTE:
 * This file's SOLE purpose is to receive PayHero's webhook callback,
 * save a local log of the transaction, and reply to PayHero with a "200 OK"
 * so that PayHero knows the callback was successfully received and doesn't
 * retry sending it.
 *
 * The actual POS payment detection happens via POLLING in the WantaPOS
 * desktop app (it queries PayHero every 3 seconds while waiting).
 * The desktop app DOES NOT rely on this file to complete the sale, because
 * the local POS app cannot receive incoming internet requests directly.
 *
 * KAAZILINK RELAY:
 * If the external_reference in the payload starts with "KZL-", the payload
 * is also forwarded to the Kaazilink application endpoint. This does NOT
 * affect MyPos in any way — MyPos ignores this file's forwarding entirely.
 *
 * LOGGING:
 * A log file named "payhero_callbacks.log" will automatically be created in
 * the same folder as this script to record all transactions for your records.
 * ========================================================================
 */

// ---------------------------------------------------------------------------
// KAAZILINK CONFIGURATION
// Change KAAZILINK_CALLBACK_URL to the real production URL once deployed,
// or the ngrok tunnel URL during local development.
// ---------------------------------------------------------------------------
define('KAAZILINK_CALLBACK_URL', 'https://kaazilink.onrender.com/api/payments/callback/payhero');


// Reference prefix that uniquely identifies a Kaazilink transaction.
// Every Kaazilink payment reference is created as "KZL-XXXXXXXX".
define('KAAZILINK_REF_PREFIX', 'KZL-');

// ---------------------------------------------------------------------------
// 1. Set headers to accept JSON  (unchanged from MyPos version)
// ---------------------------------------------------------------------------
header("Content-Type: application/json");

// ---------------------------------------------------------------------------
// 2. Read the raw POST data from PayHero  (unchanged from MyPos version)
// ---------------------------------------------------------------------------
$stkCallbackResponse = file_get_contents('php://input');
$logFile = "payhero_callbacks.log";

// ---------------------------------------------------------------------------
// 3. Log the response to a local file  (unchanged from MyPos version)
// ---------------------------------------------------------------------------
if ($stkCallbackResponse) {
    $timestamp = date("Y-m-d H:i:s");

    // Attempt to decode the JSON to pretty-print in the log
    $decodedData = json_decode($stkCallbackResponse, true);

    $logEntry = "[$timestamp] Callback Received:\n";
    if (json_last_error() === JSON_ERROR_NONE) {
        $logEntry .= json_encode($decodedData, JSON_PRETTY_PRINT) . "\n";
    } else {
        $logEntry .= $stkCallbackResponse . "\n";
    }
    $logEntry .= str_repeat("-", 50) . "\n";

    // Write to the log file securely
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

// ---------------------------------------------------------------------------
// 4. KAAZILINK RELAY — only runs when the reference starts with "KZL-"
//    MyPos is completely unaffected: it polls PayHero directly and never
//    reads this file's output or depends on this forwarding in any way.
// ---------------------------------------------------------------------------
$decodedData = $decodedData ?? json_decode($stkCallbackResponse, true);

if (is_array($decodedData)) {
    // PayHero sends the payment reference under multiple possible keys.
    // Check both the nested "response" object and the flat payload root.
    // IMPORTANT: PayHero STK Push callbacks use "User_Reference" as the primary key.
    $response = $decodedData['response'] ?? [];
    $ref = $response['User_Reference']
        ?? $response['user_reference']
        ?? $response['ExternalReference']
        ?? $response['external_reference']
        ?? $response['Reference']
        ?? $response['reference']
        ?? $response['CheckoutRequestID']
        ?? $decodedData['User_Reference']
        ?? $decodedData['user_reference']
        ?? $decodedData['ExternalReference']
        ?? $decodedData['external_reference']
        ?? $decodedData['Reference']
        ?? $decodedData['reference']
        ?? $decodedData['CheckoutRequestID']
        ?? '';

    if (strncmp((string) $ref, KAAZILINK_REF_PREFIX, strlen(KAAZILINK_REF_PREFIX)) === 0) {
        // This is a Kaazilink transaction — forward the full original payload
        $ch = curl_init(KAAZILINK_CALLBACK_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $stkCallbackResponse);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json',
        ]);
        // Non-blocking: wait at most 10 seconds; don't let Kaazilink latency
        // delay our 200 OK response back to PayHero.
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

        $relayResponse = curl_exec($ch);
        $relayCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $relayError = curl_error($ch);
        curl_close($ch);

        // Append relay result to the log for debugging
        $relayLog = "[" . date("Y-m-d H:i:s") . "] Kaazilink relay → ref={$ref}"
            . " HTTP={$relayCode}"
            . ($relayError ? " ERROR={$relayError}" : " OK={$relayResponse}")
            . "\n" . str_repeat("-", 50) . "\n";
        file_put_contents($logFile, $relayLog, FILE_APPEND);
    }
}

// ---------------------------------------------------------------------------
// 5. Return success response to PayHero to acknowledge receipt  (unchanged)
//    PayHero requires a 200 OK HTTP status and a JSON success indicator.
// ---------------------------------------------------------------------------
echo json_encode([
    "success" => true,
    "message" => "Callback received and logged successfully."
]);
?>