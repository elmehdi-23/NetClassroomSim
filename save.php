<?php
include('lang.php');
// Simple save endpoint storing to students.json
$file = 'students.json';
if(file_exists($file)){
    $json = json_decode(file_get_contents($file), true);
    if(!is_array($json)) $json = ['students'=>[]];
} else {
    $json = ['students'=>[]];
}

$ip = $_SERVER['REMOTE_ADDR'];
$time = date('Y-m-d H:i:s');
$names = array_values(array_filter($_POST['names'] ?? []));
$avatar_raw = $_POST['avatar'] ?? null;
$avatar = $avatar_raw ? basename($avatar_raw) : null;

if(empty($names)){
    echo '⚠️ No names sent.';
    exit;
}

$entry = [
    'names' => $names,
    'ip' => $ip,
    'time' => $time,
    'avatar' => $avatar ? 'avatars/' . $avatar : null
];
$json['students'][] = $entry;
file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo htmlspecialchars($lang[$current]['welcome']).' ' . htmlspecialchars(implode(', ', $names)) . ' 🎉<br>IP: ' . htmlspecialchars($ip) . '<br>'.htmlspecialchars($lang[$current]['time']).': ' . htmlspecialchars($time);
if($entry['avatar']) echo '<br><img src="' . htmlspecialchars($entry['avatar']) . '" style="width:40px;height:40px;vertical-align:middle">';
?>