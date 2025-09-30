<?php 
include('lang.php'); 
$ip = $_SERVER['REMOTE_ADDR'];

// Reset table only if from localhost
if (isset($_GET['reset']) && in_array($ip, ['127.0.0.1','::1'])) {
    file_put_contents("students.json", json_encode([]));
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
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-end mb-2">
    <a class="btn btn-sm btn-outline-primary me-1" href="?lang=fr">🇫🇷 FR</a>
    <a class="btn btn-sm btn-outline-success" href="?lang=en">us EN</a>
  </div>

  <h2 class="mb-3"><?= htmlspecialchars($lang[$current]['list_students']) ?></h2>

  <?php
  $file = 'students.json';
  $json = file_exists($file) ? json_decode(file_get_contents($file), true) : ['students'=>[]];
  $students = $json['students'] ?? [];
  ?>

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
    <?php foreach($students as $i => $s): ?>
      <tr>
        <td><?= $i+1 ?></td>
        <td>
          <?php if(!empty($s['avatar']) && file_exists($s['avatar'])): ?>
            <img src="<?= htmlspecialchars($s['avatar']) ?>" alt="avatar" style="width:48px;height:48px;border-radius:8px">
          <?php else: ?>
            <div style="width:48px;height:48px;border-radius:8px;background:#eee;display:inline-block"></div>
          <?php endif; ?>
        </td>
        <td><?= htmlspecialchars(implode(', ', $s['names'])) ?></td>
        <td><?= htmlspecialchars($s['ip']) ?></td>
        <td><?= htmlspecialchars($s['time']) ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="mt-3">
    <a href="index.php" class="btn btn-outline-primary"><?= htmlspecialchars($lang[$current]['back']) ?></a>
	<?php if (in_array($ip, ['127.0.0.1','::1'])): ?>
    <a href="table.php?reset=1" class="btn btn-outline-danger">
      🔄 <?= htmlspecialchars($lang[$current]['reset_table']) ?>
    </a>
	<?php endif; ?>
  </div>
  
</div>
</body>
</html>