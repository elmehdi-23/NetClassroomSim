<?php 
include('lang.php'); 
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// Reset table only if from localhost
if (isset($_GET['reset']) && in_array($ip, ['127.0.0.1','::1'])) {
    file_put_contents("students.json", json_encode(['students'=>[]], JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
    header("Location: table.php");
    exit;
}
?>
<!doctype html>
<html lang="<?= $current ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="shortcut icon" href="assets/icon.png">
  <title><?= htmlspecialchars($lang[$current]['list_students']) ?></title>
  <link rel="stylesheet" href="libs/bootstrap.min.css">
  <link href="css/style.css" rel="stylesheet">
  <script>
    // Auto-refresh table every 2.5 seconds
    setInterval(() => {
        window.location.reload();
    }, 2500);
  </script>
  <style>
    .small-card { padding:10px; border-radius:8px; background:#fff; box-shadow:0 2px 6px rgba(0,0,0,0.06); }
  </style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-end mb-2">
    <a class="btn btn-sm btn-outline-primary me-1" href="?lang=fr">🇫🇷 FR</a>
    <a class="btn btn-sm btn-outline-success me-1" href="?lang=en">🇺🇸 EN</a>
    <a class="btn btn-sm btn-outline-dark" href="?lang=ar">🇲🇦 AR</a>
  </div>

  <h2 class="mb-1"><?= htmlspecialchars($lang[$current]['list_students']) ?></h2>

  <?php
  // Load students
  $file = 'students.json';
  $json = file_exists($file) ? json_decode(file_get_contents($file), true) : ['students'=>[]];
  if(!is_array($json)) $json = ['students'=>[]];
  $students = $json['students'] ?? [];

  // Calculate total number of students (sum of names arrays)
  $totalStudents = 0;
  foreach($students as $s) {
      if(isset($s['names']) && is_array($s['names'])) $totalStudents += count($s['names']);
  }

  // Flatten per-person records for leaderboard calculations
  $flat = [];
  foreach($students as $entry) {
      $avatar = $entry['avatar'] ?? null;
      $time = $entry['time'] ?? null;
      $entry_ip = $entry['ip'] ?? null;
      $names = $entry['names'] ?? [];
      foreach($names as $n) {
          $flat[] = ['name'=>$n, 'avatar'=>$avatar, 'time'=>$time, 'ip'=>$entry_ip];
      }
  }

  // Leaderboard stats
  $leaderboard = [];
  // 1) Fastest to connect => earliest time in current list
  usort($flat, function($a,$b){
      $ta = strtotime($a['time'] ?? '1970-01-01 00:00:00');
      $tb = strtotime($b['time'] ?? '1970-01-01 00:00:00');
      return $ta <=> $tb;
  });
  if(count($flat)>0){
      $first = $flat[0];
      $leaderboard['first_connect'] = $first;
  }

  // 2) Most connections (count by name)
  $counts = [];
  foreach($flat as $f) {
      $name = $f['name'] ?? '—';
      if(!isset($counts[$name])) $counts[$name] = 0;
      $counts[$name] += 1;
  }
  if(count($counts)>0){
      arsort($counts);
      $top = array_key_first($counts);
      $leaderboard['most_connections'] = ['name'=>$top, 'count'=>$counts[$top]];
  }

  // 3) Most unique avatars per student name (count distinct avatars assigned to that name)
  $uniqAv = [];
  foreach($flat as $f){
      $n = $f['name'] ?? '—';
      $a = $f['avatar'] ?? null;
      if($a){
          if(!isset($uniqAv[$n])) $uniqAv[$n] = [];
          $uniqAv[$n][$a] = true;
      }
  }
  $uniqCounts = [];
  foreach($uniqAv as $n=>$map){
      $uniqCounts[$n] = count($map);
  }
  if(count($uniqCounts)>0){
      arsort($uniqCounts);
      $topn = array_key_first($uniqCounts);
      $leaderboard['most_unique_avatars'] = ['name'=>$topn, 'count'=>$uniqCounts[$topn]];
  }

  // 4) Most popular avatar overall
  $avatarCounts = [];
  foreach($flat as $f){
      $a = $f['avatar'] ?? null;
      if($a){
          if(!isset($avatarCounts[$a])) $avatarCounts[$a] = 0;
          $avatarCounts[$a] += 1;
      }
  }
  if(count($avatarCounts)>0){
      arsort($avatarCounts);
      $topA = array_key_first($avatarCounts);
      $leaderboard['most_popular_avatar'] = ['avatar'=>$topA, 'count'=>$avatarCounts[$topA]];
  }

  // Helper: detect private IP
  function is_private_ip($ip){
      if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)){
          $long = ip2long($ip);
          if($long !== false){
              if (($long & 0xFF000000) == 0x0A000000) return true; // 10.0.0.0/8
              if (($long & 0xFFF00000) == 0xAC100000) return true; // 172.16.0.0/12
              if (($long & 0xFFFF0000) == 0xC0A80000) return true; // 192.168.0.0/16
              if (($long & 0xFF000000) == 0x7F000000) return true; // 127.0.0.0/8
          }
      }
      if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)){
          if($ip === '::1') return true;
          if(strpos($ip, 'fe80') === 0) return true;
      }
      return false;
  }

  // Helper: display hostname or geolocation (public IP) with a small cache to avoid repeated external calls
  function ip_display($ip){
      if(!$ip) return '—';
      if(is_private_ip($ip)){
          $host = @gethostbyaddr($ip);
          if($host && $host !== $ip) return htmlspecialchars($host) . "<br><small class='text-muted'>($ip)</small>";
          return "Local <br><small class='text-muted'>(" . htmlspecialchars($ip) . ")</small>";
      } else {
          $cache_file = 'ipgeo_cache.json';
          $cache = file_exists($cache_file) ? json_decode(file_get_contents($cache_file), true) : [];
          if(isset($cache[$ip]) && isset($cache[$ip]['ts']) && (time() - $cache[$ip]['ts'] < 86400)){
              $geo = $cache[$ip]['geo'];
          } else {
              // try external lookup (ip-api.com); may fail if outgoing connections are blocked
              $geo = null;
              $url = "http://ip-api.com/json/".urlencode($ip)."?fields=status,country,regionName,city,isp,query";
              $ctx = stream_context_create(['http'=>['timeout'=>2]]);
              $res = @file_get_contents($url, false, $ctx);
              if($res){
                  $arr = @json_decode($res, true);
                  if($arr && ($arr['status'] ?? '') === 'success'){
                      $geo = $arr;
                      $cache[$ip] = ['ts'=>time(), 'geo'=>$geo];
                      @file_put_contents($cache_file, json_encode($cache, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
                  }
              }
          }
          if($geo){
              $parts = [];
              if(!empty($geo['city'])) $parts[] = $geo['city'];
              if(!empty($geo['regionName'])) $parts[] = $geo['regionName'];
              if(!empty($geo['country'])) $parts[] = $geo['country'];
              $loc = implode(', ', $parts);
              if(empty($loc)) $loc = $geo['isp'] ?? $ip;
              return htmlspecialchars($loc) . "<br><small class='text-muted'>(" . htmlspecialchars($ip) . ")</small>";
          } else {
              return htmlspecialchars($ip);
          }
      }
  }

  ?>

  <div class="mb-3">
    <div class="d-flex align-items-center justify-content-between mb-2">
      <div>
        <strong><?= $totalStudents ?></strong>
        <span class="text-muted"><?= htmlspecialchars($lang[$current]['names']) ?></span>
      </div>
      <div class="d-flex gap-2">
        <?php if (in_array($ip, ['127.0.0.1','::1'])): ?>
          <a href="table.php?reset=1" class="btn btn-sm btn-outline-danger">🔄 <?= htmlspecialchars($lang[$current]['reset_table']) ?></a>
        <?php endif; ?>
        <a href="index.php" class="btn btn-sm btn-outline-primary"><?= htmlspecialchars($lang[$current]['back']) ?></a>
      </div>
    </div>

    <!-- Leaderboard -->
    <div class="row g-2">
      <div class="col-md-3">
        <div class="small-card">
          <strong>Fastest (first) to connect</strong><br>
          <?php if(isset($leaderboard['first_connect'])): 
              $fc = $leaderboard['first_connect'];
              echo htmlspecialchars($fc['name'] ?? '—') . "<br><small class='text-muted'>".htmlspecialchars($fc['time'] ?? '—')."</small>";
            else: echo "<span class='text-muted'>—</span>"; endif; ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-card">
          <strong>Most connections</strong><br>
          <?php if(isset($leaderboard['most_connections'])):
              echo htmlspecialchars($leaderboard['most_connections']['name']) . "<br><small class='text-muted'>".$leaderboard['most_connections']['count']."</small>";
            else: echo "<span class='text-muted'>—</span>"; endif; ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-card">
          <strong>Most unique avatars</strong><br>
          <?php if(isset($leaderboard['most_unique_avatars'])):
              echo htmlspecialchars($leaderboard['most_unique_avatars']['name']) . "<br><small class='text-muted'>".$leaderboard['most_unique_avatars']['count']."</small>";
            else: echo "<span class='text-muted'>—</span>"; endif; ?>
        </div>
      </div>
      <div class="col-md-3">
        <div class="small-card">
          <strong>Top avatar</strong><br>
          <?php if(isset($leaderboard['most_popular_avatar'])):
              echo "<img src=\"".htmlspecialchars($leaderboard['most_popular_avatar']['avatar'])."\" style='width:40px;height:40px;vertical-align:middle'> <small class='text-muted'>".$leaderboard['most_popular_avatar']['count']."</small>";
            else: echo "<span class='text-muted'>—</span>"; endif; ?>
        </div>
      </div>
    </div>
  </div>

  <table class="table table-striped">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th><?= htmlspecialchars($lang[$current]['avatar']) ?></th>
        <th><?= htmlspecialchars($lang[$current]['names']) ?></th>
        <th><?= htmlspecialchars($lang[$current]['ip']) ?></th>
        <th><?= htmlspecialchars($lang[$current]['time']) ?></th>
      </tr>
    </thead>
    <tbody>
    <?php
      $i = 1;
      foreach($students as $s):
    ?>
      <tr>
        <td><?= $i++; ?></td>
        <td><?php if(!empty($s['avatar'])): ?><img src="<?= htmlspecialchars($s['avatar']) ?>" style="width:48px;height:48px;border-radius:6px"><?php endif; ?></td>
        <td><?= htmlspecialchars(implode(', ', $s['names'] ?? [])) ?></td>
        <td><?= ip_display($s['ip'] ?? null) ?></td>
        <td><?= htmlspecialchars($s['time'] ?? '') ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="mt-3 text-muted small">
    <em>Notes: hostnames are displayed for local (private) IPs when available; public IPs are shown with geolocation (cached).</em>
  </div>

</div>
</body>
</html>
