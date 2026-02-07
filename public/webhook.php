<?php

// Konfigurasi
$secret = 'adaSecretGithubgaBang123321123321'; // Ganti dengan secret yang Anda buat di GitHub
$branch = 'main'; // Branch yang akan di-pull
$logFile = '../storage/logs/webhook.log';

// Verifikasi Signature (Keamanan)
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';
$payload = file_get_contents('php://input');

if ($secret) {
    if (!$signature) {
        http_response_code(403);
        die('Signature missing.');
    }

    list($algo, $hash) = explode('=', $signature, 2);
    $payloadHash = hash_hmac($algo, $payload, $secret);

    if (!hash_equals($hash, $payloadHash)) {
        http_response_code(403);
        die('Invalid signature.');
    }
}

// Log aktivitas
function writeLog($message) {
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $message . "\n", FILE_APPEND);
}

writeLog("Webhook received from GitHub.");

// Eksekusi git pull
// Pastikan user web server (misal www-data) punya hak akses ke .git dan bisa menjalankan git
$commands = [
    'echo $PWD',
    'whoami',
    'git pull origin ' . $branch,
    'php artisan migrate --force', // Opsional: Jalankan migrasi otomatis
    // 'php artisan optimize:clear', // Opsional: Clear cache
];

$output = '';
foreach ($commands as $command) {
    $output .= "$ " . $command . "\n";
    $tmp = shell_exec($command . " 2>&1"); // Tangkap stderr juga
    $output .= $tmp . "\n";
}

writeLog("Output:\n" . $output);

echo "Deployment successful.\n";
echo "<pre>$output</pre>";
