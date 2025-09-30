<?php include('lang.php'); ?>
<!doctype html>
<html lang="<?= $current ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="shortcut icon" href="assets/icon.png">
  <title><?= htmlspecialchars($lang[$current]['title']) ?></title>
  <!-- CDN libs so project runs without local libs -->
  <link rel="stylesheet" href="libs/bootstrap.min.css">
  <link rel="stylesheet" href="libs/animate.min.css">
  <link href="css/style.css" rel="stylesheet">
  <script src="libs/jquery-3.7.0.min.js"></script>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-end mb-2">
    <a class="btn btn-sm btn-outline-primary me-1" href="?lang=fr">🇫🇷 FR</a>
    <a class="btn btn-sm btn-outline-success" href="?lang=en">🇬🇧 EN</a>
  </div>

  <h1 class="mb-3 text-center animate__animated animate__bounceIn"><?= htmlspecialchars($lang[$current]['title']) ?></h1>
  <p class="lead text-center"><?= htmlspecialchars($lang[$current]['enter_names']) ?></p>

  <div class="card shadow-sm p-4 container-small">
    <form id="nameForm" method="post">
      <input type="text" name="names[]" class="form-control mb-2" placeholder="<?= htmlspecialchars($lang[$current]['name_placeholder1']) ?>" required>
      <input type="text" name="names[]" class="form-control mb-2" placeholder="<?= htmlspecialchars($lang[$current]['name_placeholder2']) ?>">
		<div class="mb-3">
		  <input type="text" class="form-control" id="name3" name="names[]" placeholder="<?= htmlspecialchars($lang[$current]['name_placeholder3']) ?>" disabled>
		  <div class="form-check mt-2">
			<input class="form-check-input" type="checkbox" id="check3">
			<label class="form-check-label" for="check3">
			  <?= htmlspecialchars($lang[$current]['confirm_presence']) ?>
			</label>
		  </div>
		</div>
      <h5 class="mt-3"><?= htmlspecialchars($lang[$current]['choose_avatar']) ?></h5>
      <div class="d-flex justify-content-between align-items-center my-3" id="avatarList">
        <?php for($i=1;$i<=5;$i++): $fname = "avatars/avatar{$i}.svg"; ?>
          <label class="avatar-choice" data-fname="<?= $fname ?>">
            <input type="radio" name="avatar" value="<?= htmlspecialchars($fname) ?>" <?= $i===1? 'checked': '' ?> />
            <img src="<?= $fname ?>" alt="Avatar <?= $i ?>" />
          </label>
        <?php endfor; ?>
      </div>

      <button type="submit" class="btn btn-success w-100"><?= htmlspecialchars($lang[$current]['connect']) ?></button>
    </form>
    <div id="response" class="mt-3"></div>
  </div>

  <div class="text-center mt-3">
    <a href="table.php" class="btn btn-outline-secondary">View connected students</a>
  </div>
</div>

<script>
$(function(){
  // avatar selection UI
  $('#avatarList .avatar-choice').on('click', function(){
    $('#avatarList .avatar-choice').removeClass('selected');
    $(this).addClass('selected');
    $(this).find('input[type=radio]').prop('checked', true);
  });
  $('#avatarList .avatar-choice').first().addClass('selected');

  // check localStorage for one-time submission
	if(localStorage.getItem("names_submitted")) {
	  $("#nameForm").hide();
	  $("#response").html(`
		<div class="alert alert-info">
		  ✅ Vous avez déjà rejoint le serveur !
		  <br>
		  <button id="resetBtn" class="btn btn-warning mt-2">🔄 Rejoindre à nouveau</button>
		</div>
	  `);

	  // Reset button action
	  $(document).on("click", "#resetBtn", function(){
		localStorage.removeItem("names_submitted");
		location.reload(); // reloads page so they can re-enter names
	  });
	}
	// Enable Student 3 input only if checkbox is ticked
	$(document).on("change", "#check3", function(){
	  if($(this).is(":checked")){
		$("#name3").prop("disabled", false);
	  } else {
		$("#name3").prop("disabled", true).val("");
	  }
	});

  $('#nameForm').on('submit', function(e){
    e.preventDefault();
    $.post('save.php', $(this).serialize(), function(data){
      $('#response').html('<div class="alert alert-success animate__animated animate__fadeIn">' + data + '</div>');
      $('#nameForm').hide();
      localStorage.setItem('names_submitted','true');
    });
  });
});
</script>
</body>
</html>