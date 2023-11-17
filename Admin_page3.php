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

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

      $fname = isset($_POST['fname']) ? $_POST['fname'] : "";
      $mname = isset($_POST['mname']) ? $_POST['mname'] : "";
      $lname = isset($_POST['lname']) ? $_POST['lname'] : "";
      $ugend = isset($_POST['ugend']) ? $_POST['ugend'] : "";
      switch ($ugend) {
        case 'Male':
            $ugend = 'M';
            break;
        case 'Female':
            $ugend = 'F';
            break;
        case 'Transgender':
            $ugend = 'T';
            break;
        default:
            // Handle default case or validation
            break;
    }
      // Use prepared statement to prevent SQL injection
      $updatePersonStmt = $conn->prepare("UPDATE PERSON SET FirstName=?, MiddleName=?, LastName=?, Gender=? WHERE PersonID=?");
      $updatePersonStmt->bind_param("ssssi", $fname, $mname, $lname, $ugend, $_SESSION["personid"]);
      $updatePersonStmt->execute();
      $updatePersonStmt->close();

      if ($_SESSION["phchanged"] == "true") {
          $deletePhoneStmt = $conn->prepare("DELETE FROM PHONE WHERE PersonID=?");
          $deletePhoneStmt->bind_param("i", $_SESSION["personid"]);
          $deletePhoneStmt->execute();
          $deletePhoneStmt->close();

          $phones = isset($_POST['phones']) ? $_POST['phones'] : array();

$insertPhoneStmt = $conn->prepare("INSERT INTO PHONE VALUES (?, ?)");
$insertPhoneStmt->bind_param("ii", $_SESSION["personid"], $phone);

foreach ($phones as $phone) {
    $insertPhoneStmt->execute();
}

$insertPhoneStmt->close();

          $_SESSION["phchanged"] = "false";
      }
    }


    if ($_SESSION["usertype"] == "instructor") {
      // Check if "insdept" is set in the $_POST array
      if (isset($_POST['insdept'])) {
          $insdept = $_POST['insdept'];
  
          // Use prepared statement to prevent SQL injection
          $stmt = $conn->prepare("SELECT DeptNo FROM DEPARTMENT WHERE DeptName = ?");
          $stmt->bind_param("s", $insdept);
          $stmt->execute();
          $res = $stmt->get_result();
  
          // Check if the query was successful
          if ($res) {
              $deptData = $res->fetch_assoc();
  
              // Check if the result contains the "DeptNo" key
              if (isset($deptData['DeptNo'])) {
                  $deptno = $deptData['DeptNo'];
  
                  $sql = "UPDATE INSTRUCTOR SET DeptNo = ? WHERE InstructorID = ?";
                  $stmt = $conn->prepare($sql);
                  $stmt->bind_param("is", $deptno, $_SESSION["userid"]);
                  $stmt->execute();
              } else {
                  echo "Department not found.";
              }
          } else {
              echo "Error in the query.";
          }
      } 
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
  <input type="submit" value="SUBMIT" class="btn-submit rect-circ"/>

  <div class="user-type-label">
    <?php
      if ($_SESSION["usertype"] == "student")
        echo '<span>STUDENT</span>';
      elseif ($_SESSION["usertype"] == "instructor")
        echo '<span>INSTRUCTOR</span>';
    ?>
  </div>

  <?php
    $userarray = array();
    if ($_SESSION["usertype"] == "student") {
      $sql = "SELECT StudentID, FirstName, MiddleName, LastName, Gender, STUDENT.PersonID
              FROM STUDENT, PERSON
              WHERE STUDENT.PersonID = PERSON.PersonID
              AND StudentID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $_SESSION["personid"] = $row['PersonID'];
          array_push($userarray,
            $row['StudentID'], $row['FirstName'], $row['MiddleName'], $row['LastName'], $row['Gender']);
        }
      }
    }

    elseif ($_SESSION["usertype"] == "instructor") {
      $sql = "SELECT InstructorID, FirstName, MiddleName, LastName, Gender, PERSON.PersonID
              FROM INSTRUCTOR, PERSON
              WHERE INSTRUCTOR.PersonID = PERSON.PersonID
              AND InstructorID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $_SESSION["personid"] = $row['PersonID'];
          array_push($userarray,
            $row['InstructorID'], $row['FirstName'], $row['MiddleName'], $row['LastName'], $row['Gender']);
        }
      }
    }

    echo '<div class="user-person">
      <div class="rect-round-sm">'.$userarray[0].'</div>
      <input type="text" name="fname" value="'.$userarray[1].'" class="edit-id rect-round-sm">
      <input type="text" name="mname" value="'.$userarray[2].'" class="edit-id rect-round-sm">
      <input type="text" name="lname" value="'.$userarray[3].'" class="edit-id rect-round-sm">
      
      <select name="ugend" class="rect-round-sm">';
        if ($userarray[4] == 'M')
          echo '<option selected="selected">Male</option>';
        else
          echo '<option>Male</option>';
        
        if ($userarray[4] == 'F')
          echo '<option selected="selected">Female</option>';
        else
          echo '<option>Female</option>';

        if ($userarray[4] == 'T')
          echo '<option selected="selected">Transgender</option>';
        else
          echo '<option>Transgender</option>';
      echo '</select>
    </div>';
  ?>

  <?php
    $sql = "SELECT PhNo FROM PHONE WHERE PersonID = '";
    $sql .= $_SESSION["personid"]."'";
    $res = $conn->query($sql);

    $phones = array(); $pcnt = 0;
if ($res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        array_push($phones, $row['PhNo']);
        $pcnt++;
        
        if ($pcnt == 3) break;
    }
}

$firstPhoneNumber = isset($phones[0]) ? $phones[0] : "";

echo '<div class="label-phone"><span>PHONE:</span></div>';
echo '<input type="number" class="phno rect-round-sm" name="phones[]" value="' . $firstPhoneNumber . '" maxlength="10">';

    $_SESSION["phchanged"] = "true";


    if ($_SESSION["usertype"] == "instructor") {
      echo '<div class="label-dept"><span>DEPARTMENT:</span></div>
      <select class="user-dept-sel rect-round-sm" name="insdept">';

      $departments = array();
      $sql = "SELECT * FROM DEPARTMENT ORDER BY DeptNo";
      $res = $conn->query($sql);

      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          array_push($departments, array($row['DeptNo'], $row['DeptName']));
        }
      }

      $sql = "SELECT DeptNo FROM INSTRUCTOR WHERE InstructorID = '";
      $sql .= $_SESSION["userid"]."'";
      $res = $conn->query($sql);

      $deptno = 0;
      if ($res->num_rows > 0) {
        while ($row = $res->fetch_assoc()) {
          $deptno = $row['DeptNo'];
        }
      }

      for ($i = 0; $i < COUNT($departments); $i++) {
        if ($deptno == $departments[$i][0])
          echo '<option selected="selected" name="insdept" value="'.$departments[$i][1].'">'.$departments[$i][1].'</option>';
        else
          echo '<option name="insdept" value="'.$departments[$i][1].'">'.$departments[$i][1].'</option>';
      }
      echo '</select>';
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