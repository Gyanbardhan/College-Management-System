<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Instructor</title>

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

<div class="user-label rect-circ">
  <span class="rect-circ">INSTRUCTOR</span>
</div>

<div class="btn-logout rect-circ" onClick="logout()">
  <span class="rect-circ">LOGOUT</span>
  <div class="rect-circ">✖</div>
</div>

<div class="dept-label">
  <?php
    $sql = "SELECT DEPARTMENT.DeptNo, DEPARTMENT.DeptName
            FROM INSTRUCTOR, DEPARTMENT
            WHERE INSTRUCTOR.InstructorID = ".$_SESSION["userid"].
            " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        echo "<span>".$row['DeptName']."</span>";
        $_SESSION["deptid"] = $row['DeptNo'];
      }
    }
  ?>
</div>

<?php
  $sql = "SELECT * FROM HEAD WHERE Head = '";
  $sql .= $_SESSION["userid"]."'";
  $res = $conn->query($sql);
  if ($res->num_rows > 0) {
    echo
    '<div class="rect-circ btn-course-edit" onclick="location.href = '."'course-info.php'".'">
      <span>EDIT DEPARTMENT COURSES</span>
    </div>';
  }
?>

<div class="user-info">
  <?php
    $sql = "SELECT InstructorID, PersonID FROM INSTRUCTOR
            WHERE INSTRUCTOR.InstructorID = ".$_SESSION["userid"];
    $res = $conn->query($sql);

    $pid = -1;
    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $pid = $row['PersonID'];
      }
    }

    $sql = "SELECT * FROM PERSON WHERE PERSON.PersonID = ".$pid;
    $res = $conn->query($sql);

    if ($res->num_rows > 0) {
      while ($row = $res->fetch_assoc()) {
        $name = $row['FirstName']." ";
        if ($row['MiddleName'] != "")
          $name .= $row['MiddleName']." ";
        $name .= $row['LastName'];
      }
    }

    echo "<span>$name</span>";
    echo "<span>"; echo $_SESSION["userid"]; echo "</span>";
  ?>
</div>

<?php
  $courses = array();

  $sql = "SELECT COURSE.CourseID, COURSE.CourseName, CCOUNT.Cnt, COURSE.ClassesTaken
          FROM COURSE, (
                        SELECT UNDERTAKES.CourseID, COUNT(UNDERTAKES.StudentID) AS Cnt
                        FROM COURSE, UNDERTAKES
                        WHERE COURSE.InstructorID = ".$_SESSION["userid"].
                        " AND COURSE.CourseID = UNDERTAKES.CourseID
                        GROUP BY UNDERTAKES.CourseID) CCOUNT
          WHERE COURSE.CourseID = CCOUNT.CourseID";
  $res = $conn->query($sql);
  
  if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
      array_push($courses,
        array($row['CourseID'], $row['CourseName'], $row['Cnt'], $row['ClassesTaken']));
    }
  }

  for ($i = 0; $i < COUNT($courses); $i++) {
    echo
    '<div class="ins-course-container">
      <div class="rect-round-sm std-course-cred" id="'.$courses[$i][0].'" onclick="send('.$courses[$i][0].')">
        <div class="rect-round-sm std-course-id">
          <span>'.$courses[$i][0].'</span>
        </div>
    
        <div class="std-course-name">
          <span>'.$courses[$i][1].'</span>
        </div>
      </div>

      <div class="rect-round-sm">
        <span><strong>'.$courses[$i][2].'</strong> students</span>
      </div>

      <div class="rect-round-sm">
        <span><strong>'.$courses[$i][3].'</strong> classes</span>
      </div>
    </div>';
  }
?>

</div>

<script>
  function logout() {
    document.cookie = "courseid" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "loggedin" + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
    document.cookie = "logout=yes";
    window.location.href = 'Login_page.php';
  }

  function send(str) {
    document.cookie = "courseid=" + str.id;
    window.location.href = "Instructor_page2.php";
  }
</script>

</body>

</html>
