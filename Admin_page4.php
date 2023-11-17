<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Administrator - Add User</title>

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
</head>

<body>

<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
</div>

<div class="btn-logout rect-circ" onClick="goBack()">
  <span class="rect-circ">BACK</span>
  <div class="rect-circ"><-</div>
</div>

<form class="admin-buttons">
  <a href="Admin_page6.php" class="btn-admin rect-circ">
    <span>ADD STUDENT</span>
  </a>

  <a href="Admin_page7.php" class="btn-admin rect-circ">
    <span>ADD INSTRUCTOR</span>
  </a>
</form>

</div>
</div>
<script>
  function goBack() {
    window.location.href = 'Admin_page1.php';
  }
</script>
</body>

</html>
