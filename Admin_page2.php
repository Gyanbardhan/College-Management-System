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

<?php
  $servname = "localhost";
  $conn = new mysqli($servname, "root","", "college_db");

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);
?>

<div class="page-container">

<div class="content-wrap">

<div class="user-label rect-circ">
  <span class="rect-circ">ADMINISTRATOR</span>
</div>

<div class="btn-logout rect-circ" onClick="goBack()">
  <span class="rect-circ">BACK</span>
  <div class="rect-circ"><-</div>
</div>

<form method="post" action="<?php htmlspecialchars($_SERVER['PHP_SELF']) ?>">
  <input type="submit" value="SUBMIT" class="btn-submit rect-circ" style="margin-top: 6em"/>

  <?php
    $sql = "SELECT COUNT(*) FROM DEPARTMENT";
    $res = $conn->query($sql);

    $count = 0;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $count = $row['COUNT(*)'];
      }
    }

    for ($i = 0; $i < $count; $i++) {
      for ($i = 0; $i < $count; $i++) {
        // Check if the key is set before accessing it
        if (isset($_POST['dhead'.$i])) {
            $selectedOption = substr($_POST['dhead'.$i], 0, 5);
            $sql = "UPDATE HEAD SET Head = '".$selectedOption."' WHERE DeptNo = '".($i + 1)."'";
            $conn->query($sql);
        } 
    }
      $conn->query($sql);
    }

    $department = array();
    $sql = "SELECT * FROM DEPARTMENT, HEAD
            WHERE HEAD.DeptNo = DEPARTMENT.DeptNo
            ORDER BY DEPARTMENT.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        array_push($department, array($row['DeptNo'], $row['DeptName'], $row['Head']));
      }
    }

    $instructors = array();
    for ($i = 0; $i < COUNT($department); $i++) {
      $sql = "SELECT * FROM INSTRUCTOR, PERSON
              WHERE INSTRUCTOR.DeptNo = ".$department[$i][0].
              " AND INSTRUCTOR.PersonID = PERSON.PersonID";
      $res = $conn->query($sql);

      $temp = array();
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push($temp,
            array($row['InstructorID'], $row['FirstName'], $row['MiddleName'], $row['LastName']));
        }
      }
      array_push($instructors, $temp);
    }

    for ($i = 0; $i < COUNT($department); $i++) {
      echo
      '<div class="Admin_page2-container">
        <div class="rect-round-sm Admin_page2-name">
          <span>'.$department[$i][1].'</span>
        </div>

        <select class="rect-round-sm" name="dhead'.$i.'" id="dhead'.$i.'">';
        for ($j = 0; $j < COUNT($instructors[$i]); $j++) {
          $name = $instructors[$i][$j][0].": ".$instructors[$i][$j][1]." ";
          if ($instructors[$i][$j][2] != "")
            $name .= $instructors[$i][$j][2]." ";
          $name .= $instructors[$i][$j][3];

          if ($instructors[$i][$j][0] == $department[$i][2])
            echo '<option selected="selected" value="'.$name.'">'.$name.'</option>';
          
          else
            echo '<option value="'.$name.'">'.$name.'</option>';
        }
        echo '</select>
      </div>';
    }
  ?>
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