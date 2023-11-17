<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Administrator - Delete User</title>

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

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $deleteId = isset($_POST['deleteId']) ? $_POST['deleteId'] : "";
    $isStudent = isset($_POST['isStudent']) ? $_POST['isStudent'] : false;

    if ($isStudent) {
      // Delete the row from the UNDERTAKES table
      $deleteUndertakesStmt = $conn->prepare("DELETE FROM UNDERTAKES WHERE StudentID = ?");
      $deleteUndertakesStmt->bind_param("s", $deleteId);
      $deleteUndertakesStmt->execute();
      $deleteUndertakesStmt->close();

      // Fetch PersonID from the STUDENT table
      $getPersonIdStmt = $conn->prepare("SELECT PersonID FROM STUDENT WHERE StudentID = ?");
      $getPersonIdStmt->bind_param("s", $deleteId);
      $getPersonIdStmt->execute();
      $getPersonIdStmt->bind_result($personId);
      $getPersonIdStmt->fetch();
      $getPersonIdStmt->close();

      // Delete the row from the STUDENT table
      $deleteStudentStmt = $conn->prepare("DELETE FROM STUDENT WHERE StudentID = ?");
      $deleteStudentStmt->bind_param("s", $deleteId);
      $deleteStudentStmt->execute();
      $deleteStudentStmt->close();

      // Delete the related record from the PHONE table
      $deletePhoneStmt = $conn->prepare("DELETE FROM PHONE WHERE PersonID = ?");
      $deletePhoneStmt->bind_param("i", $personId);
      $deletePhoneStmt->execute();
      $deletePhoneStmt->close();

      // Delete the row from the PERSON table
      $deletePersonStmt = $conn->prepare("DELETE FROM PERSON WHERE PersonID = ?");
      $deletePersonStmt->bind_param("i", $personId);
      $deletePersonStmt->execute();
      $deletePersonStmt->close();
    } else {
      // Update the COURSES table to replace the instructor with the head of the department
      $updateCoursesStmt = $conn->prepare("UPDATE COURSE C SET C.InstructorID = (SELECT H.Head FROM head H WHERE H.DeptNo = (SELECT distinct(D.DeptNo) FROM COURSE D WHERE D.InstructorID = ?)) WHERE C.InstructorID = ?");
      $updateCoursesStmt->bind_param("ss", $deleteId, $deleteId);
      $updateCoursesStmt->execute();
      $updateCoursesStmt->close();

      // Fetch PersonID from the INSTRUCTOR table
      $getPersonIdStmt = $conn->prepare("SELECT PersonID FROM INSTRUCTOR WHERE InstructorID = ?");
      $getPersonIdStmt->bind_param("s", $deleteId);
      $getPersonIdStmt->execute();
      $getPersonIdStmt->bind_result($personId);
      $getPersonIdStmt->fetch();
      $getPersonIdStmt->close();

      // Delete the related record from the PHONE table
      $deletePhoneStmt = $conn->prepare("DELETE FROM PHONE WHERE PersonID = ?");
      $deletePhoneStmt->bind_param("i", $personId);
      $deletePhoneStmt->execute();
      $deletePhoneStmt->close();

      // Delete the row from the INSTRUCTOR table
      $deleteInstructorStmt = $conn->prepare("DELETE FROM INSTRUCTOR WHERE InstructorID = ?");
      $deleteInstructorStmt->bind_param("s", $deleteId);
      $deleteInstructorStmt->execute();
      $deleteInstructorStmt->close();

      // Delete the row from the PERSON table
      $deletePersonStmt = $conn->prepare("DELETE FROM PERSON WHERE PersonID = ?");
      $deletePersonStmt->bind_param("i", $personId);
      $deletePersonStmt->execute();
      $deletePersonStmt->close();
    }

    // After your delete operations, add the following line to redirect
    header("Location: Admin_page1.php");
    exit(); // Make sure to exit after the header to prevent further execution
  }
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

    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="Admin_page3-edit">
      <input type="submit" value="DELETE" class="btn-submit rect-circ"/>

      <div class="user-person">
        <input type="text" name="deleteId" placeholder="StudentID or InstructorID" class="edit-id rect-round-sm">
        <br>
        <br>
        <input type="checkbox" name="isStudent" id="isStudent">
        <label for="isStudent">If deleting Student tick this.</label>
      </div>
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
