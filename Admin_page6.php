<?php
  session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Administrator - Add Student</title>

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
    $fname = isset($_POST['fname']) ? $_POST['fname'] : "";
    $mname = isset($_POST['mname']) ? $_POST['mname'] : "";
    $lname = isset($_POST['lname']) ? $_POST['lname'] : "";
    $ugend = isset($_POST['ugend']) ? $_POST['ugend'] : "";
    $phone = isset($_POST['phone']) ? $_POST['phone'] : "";
    $studentId = isset($_POST['studentId']) ? $_POST['studentId'] : "";

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
    $insertPersonStmt = $conn->prepare("INSERT INTO PERSON (PersonID, FirstName, MiddleName, LastName, Gender) VALUES (?, ?, ?, ?, ?)");
    $personId = getNewPersonId($conn);
    $insertPersonStmt->bind_param("issss", $personId, $fname, $mname, $lname, $ugend);
    $insertPersonStmt->execute();
    $insertPersonStmt->close();

    // Use prepared statement to prevent SQL injection
    $insertStudentStmt = $conn->prepare("INSERT INTO STUDENT (StudentID, PassHash, PersonID) VALUES (?, ?, ?)");
    $password = "pass1234"; // The password you want to hash
    $passHash = md5($password);
    $insertStudentStmt->bind_param("ssi", $studentId, $passHash, $personId);
    $insertStudentStmt->execute();
    $insertStudentStmt->close();

    // Use prepared statement to prevent SQL injection
    $insertPhoneStmt = $conn->prepare("INSERT INTO PHONE (PersonID, PhNo) VALUES (?, ?)");
    $insertPhoneStmt->bind_param("is", $personId, $phone);
    $insertPhoneStmt->execute();
    $insertPhoneStmt->close();
  }

  function getNewPersonId($conn) {
    $sql = "SELECT MAX(PersonID) as MaxID FROM PERSON";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $maxId = $row['MaxID'];
    return $maxId + 1;
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

  <div class="user-person">
    <input type="text" name="studentId" placeholder="STUDENT ID" class="edit-id rect-round-sm">
    <br>
    <br>
    <input type="text" name="fname" placeholder="FIRST NAME" class="edit-id rect-round-sm">
    <input type="text" name="mname" placeholder="MIDDLE NAME" class="edit-id rect-round-sm">
    <input type="text" name="lname" placeholder="LAST NAME" class="edit-id rect-round-sm">
    
    <select name="ugend" class="rect-round-sm">
      <option>Male</option>
      <option>Female</option>
      <option>Transgender</option>
    </select>
  </div>

  <div class="label-phone"><span>PHONE:</span></div>
  <input type="number" class="phno rect-round-sm" name="phone" placeholder="PHONE NUMBER" maxlength="10">

</form>

</div>

</div>
<script>
  function goBack() {
    window.location.href = 'Admin_page4.php';
  }
</script>
</body>

</html>
