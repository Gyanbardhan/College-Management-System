<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Administrator</title>

  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="//use.fontawesome.com/releases/v5.0.7/css/all.css">

  <link href="https://fonts.googleapis.com/css?family=Be+Vietnam:400,600,800&display=swap" rel="stylesheet">
</head>

<body>

<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
  <br>
</div>

<div class="btn-logout rect-circ" onClick="logout()">
  <span class="rect-circ">LOGOUT</span>
  <div class="rect-circ">✖</div>
</div>

<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="admin-buttons">
<br>
<br>
  <a href="Admin_page2.php" class="btn-admin rect-circ">
    <span>EDIT HEADS</span>
  </a>
  
  <input type="text" class="rect-round-sm admin-uid" name="userid" value="" placeholder="USER ID" maxlength="5">

  <input type="submit" class="btn-admin rect-circ" value="EDIT USER"/>
  <br>
  <a href="Admin_page4.php" class="btn-admin rect-circ">
  <span>ADD USER</span>
</a>
  <a href="Admin_page5.php" class="btn-admin rect-circ">
  <span>DELETE USER</span>
</a>
  <span id="invalid-user"></span>
</form>

<?php
  $servname = "localhost";
  $conn = new mysqli($servname, "root","", "college_db");
  
  $utype = "inv";
  $sql = "SELECT * FROM STUDENT";
  $res = $conn->query($sql);

  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      if (isset($_POST['userid']) && $row['StudentID'] == $_POST['userid']) {
        $utype = "student";
        break;
    }
    
    }
  }

  if ($utype == "inv") {
    $sql = "SELECT * FROM INSTRUCTOR";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        if (isset($_POST['userid']) && $row['InstructorID'] == $_POST['userid']) {
          $utype = "instructor";
          break;
      }
      
      }
    }  
  }

  if ($utype == "inv") {
    echo '<script>
      document.getElementById("invalid-user").innerHTML = "Invalid User ID";
    </script>';
  }
  
  else {
    echo
    '<script>
      document.getElementById("invalid-user").innerHTML = "";
    </script>';

    $_SESSION["phchanged"] = "false";
    
    $_SESSION["userid"] = $_POST["userid"];
        
    if ($utype == "student")
      $_SESSION["usertype"] = "student";
    
    elseif  ($utype == "instructor")
      $_SESSION["usertype"] = "instructor";

    header("Location: Admin_page3.php");
  }
?>

</div>

</div>

<script>
  function logout() {
    document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "logout=yes";
    window.location.href = 'Login_page.php';
  }
</script>

</body>

</html>