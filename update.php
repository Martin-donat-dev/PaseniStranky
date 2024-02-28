<?php
define("SECRET", "supersecretkey");

if ($_SERVER['REQUEST_METHOD'] != "POST")
{
    error_log("Invalid request method, terminating...");
    echo 'Invalid request method, terminating...';
    http_response_code(403);
    exit(0);
}

$body = file_get_contents('php://input');

if (!verifySignature($body))
{
    error_log("Invalid signature, terminating...");
    echo 'Invalid signature, terminating...';
    http_response_code(403);
    exit(0);
}

echo 'success';

function verifySignature($body){
    $headers = getallheaders();
    
    error_log(var_export($headers, true));

    return hash_equals('sha256='.hash_hmac('sha256', $body, SECRET), $headers['x-hub-signature-256']); 
  }