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
  $conn = new mysqli($servname, "root", "", "college_db");

  if ($conn->connect_error)
    die("Connection failed: " . $conn->connect_error);

  if (!isset($_COOKIE["courseid"]))
    echo "Enable Cookies";
  ?>

  <div class="page-container">

    <div class="user-label rect-circ">
      <span class="rect-circ">INSTRUCTOR</span>
    </div>

    <div class="btn-logout rect-circ" onClick="goBack()">
      <span class="rect-circ">BACK</span>
      <div class="rect-circ"><-</div>
    </div>

    <div class="dept-label">
      <?php
      $sql = "SELECT DEPARTMENT.DeptName
              FROM INSTRUCTOR, DEPARTMENT
              WHERE INSTRUCTOR.InstructorID = " . $_SESSION["userid"] .
        " AND INSTRUCTOR.DeptNo = DEPARTMENT.DeptNo";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          echo "<span>" . $row['DeptName'] . "</span>";
        }
      }
      ?>
    </div>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>">
      <input type="submit" value="SUBMIT" class="btn-submit rect-circ" />

      <?php
      $classesTaken = isset($_POST['clsInp']) ? $_POST['clsInp'] : '';
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Only process form data if the form has been submitted

        $courseId = $_COOKIE["courseid"];
        $newClassesTaken = $_POST["clsInp"];
        $updateCourseSql = "UPDATE COURSE SET ClassesTaken = '$newClassesTaken' WHERE CourseId = '$courseId'";
        $conn->query($updateCourseSql);

        if ($conn->error) {
          echo "Error updating COURSE record: " . $conn->error;
        } 

        $undertakesSql = "SELECT StudentID, Attendance, PaperMarks, InternalMarks FROM UNDERTAKES WHERE CourseID = '$courseId' ORDER BY StudentID";
        $undertakesResult = $conn->query($undertakesSql);

        $students = array();
        if ($undertakesResult->num_rows > 0) {
          while ($row = $undertakesResult->fetch_assoc()) {
            array_push($students, $row);
          }
        }

        for ($i = 0; $i < count($students); $i++) {
          $clsValue = isset($_POST['cls' . $i]) ? $_POST['cls' . $i] : '';
          $papValue = isset($_POST['pap' . $i]) ? $_POST['pap' . $i] : '';
          $intValue = isset($_POST['int' . $i]) ? $_POST['int' . $i] : '';

          $studentID = $students[$i]['StudentID'];
          $updateUndertakesSql = "UPDATE UNDERTAKES SET Attendance = '$clsValue', PaperMarks = '$papValue', InternalMarks = '$intValue' WHERE StudentID = '$studentID' AND CourseID = '$courseId'";
          $conn->query($updateUndertakesSql);

          if ($conn->error) {
            echo "Error updating UNDERTAKES record for StudentID $studentID: " . $conn->error;
          } 
        }
      }

      $courseId = $_COOKIE["courseid"];
      $courseSql = "SELECT CourseID, CourseName, ClassesTaken FROM COURSE WHERE CourseID = '$courseId'";
      $courseResult = $conn->query($courseSql);

      $course = null;
      if ($courseResult->num_rows > 0) {
        while ($row = $courseResult->fetch_assoc()) {
          $course = array($row['CourseID'], $row['CourseName'], $row['ClassesTaken']);
        }
      }

      echo
        '<div class="ins-course-edit-container">
      <div class="rect-round-sm std-course-cred">
        <div class="rect-round-sm std-course-id">
          <span>' . $course[0] . '</span>
        </div>

        <div class="std-course-name">
          <span>' . $course[1] . '</span>
        </div>
      </div>

      <div class="rect-round-sm" onClick="decrClass()"><span>-</span></div>

      <div class="rect-round-sm">
        <span><strong><span id="numClass">' . $course[2] . '</span></strong> classes</span>
        <input id="clsInp" type="hidden" name="clsInp" value="' . $course[2] . '" />
      </div>

      <div class="rect-round-sm" onClick="incrClass()"><span>+</span></div>
    </div>';
      ?>

      <div class="line"></div>

      <div class="ins-course-edit-label">
        <span>Internal</span>
        <span>Paper</span>
        <span>Classes</span>
      </div>

      <?php
      $sql = "SELECT *
      FROM UNDERTAKES, STUDENT, PERSON
      WHERE UNDERTAKES.CourseID = '".$_COOKIE["courseid"];
$sql .= "'AND UNDERTAKES.StudentID = STUDENT.StudentID
      AND PERSON.PersonID = STUDENT.PersonID
      ORDER BY STUDENT.StudentID";
$res = $conn->query($sql);

$students = array();
if ($res->num_rows > 0) {
while ($row = $res->fetch_assoc()) {
  $name = $row['FirstName']." ";
  if ($row['MiddleName'] != "")
    $name .= $row['MiddleName']." ";
  $name .= $row['LastName'];
  array_push($students,
    array($row['StudentID'], $name, isset($row['Attendance']) ? $row['Attendance'] : '', $row['PaperMarks'], $row['InternalMarks']));
}
}

      

      for ($i = 0; $i < COUNT($students); $i++) {
        echo
          '<div class="ins-course-edit-course">
        <div class="rect-round-sm std-course-cred">
          <div class="rect-round-sm std-course-id">
            <span>' . $students[$i][0] . '</span>
          </div>
          <div class="std-course-name">
            <span>' . $students[$i][1] . '</span>
          </div>
        </div>

        <input type="number" class="rect-round-sm"
          name="cls' . $i . '" value="' . $students[$i][2] . '" maxlength="2"/>

        <input type="number" class="rect-round-sm"
          name="pap' . $i . '" value="' . $students[$i][3] . '" maxlength="2"/>

        <input type="number" class="rect-round-sm"
          name="int' . $i . '" value="' . $students[$i][4] . '" maxlength="2"/>
      </div>';
      }
      ?>
    </form>

  </div>

  <script>
    function incrClass() {
      var temp = document.getElementById("numClass").innerHTML;
      document.getElementById("numClass").innerHTML = parseInt(temp) + 1;
      document.getElementById("clsInp").value = parseInt(temp) + 1;
    }

    function decrClass() {
      var temp = document.getElementById("numClass").innerHTML;
      document.getElementById("numClass").innerHTML = parseInt(temp) - 1;
      document.getElementById("clsInp").value = parseInt(temp) - 1;
    }

    function goBack() {
      window.location.href = 'Instructor_page1.php';
    }
  </script>
</body>

</html>