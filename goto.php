<?php 
  $url = $_SERVER['PHP_SELF'];
  $u = explode('/', $url);
  header("refresh:0.5;url=" . base64_decode($u[2])); 
?>
<!DOCTYPE HTML>
<html>
<head>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
</head>
<body>
	<h3 class="text-center"><small>Please wait a moment</small></h3>
	<h1 class="text-center"><small>You're being automatically redirected to the requested page.</small></h1>
	<h3 class="text-center"><small>If you are not forwarded automatically, <a href=<?= base64_decode($u[2]); ?>>please click here</a></small></h3>
</body>
</html>
