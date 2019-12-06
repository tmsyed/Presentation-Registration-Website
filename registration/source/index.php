<!DOCTYPE HTML>  
<html>
<head>
<style>
    .error {color: #FF0000;}
</style>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Presentation Registration</title>
<link rel="stylesheet" href="styles/style.css">
</head>
<body>  

<?php
    error_reporting(0);
    include_once 'data.php';

    $presentSlots = GetSlots();

    // define variables and set to empty values
    $umidErr = $firstNameErr = $lastNameErr = $projectTitleErr = $emailErr = $phoneNumberErr = $slotIdErr = "";
    $umid = $firstName = $lastName = $projectTitle = $email = $phoneNumber = $selectedDate = "";
    $slotId = 0;
    $readyForSubmission = true;
    $isIdUnique = false;
    $shouldResubmit = false;
    $isSubmitted = false;

    //validates data and submits if everything checks out
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["umid"])) {
            $umidErr = "UMID is required";
            $readyForSubmission = false;
        } 
        else {
            $umid = test_input($_POST["umid"]);
            //checks if UMID is numeric and exactly 8 characters long
            if(!preg_match("/^\d{8}$/", $umid)) {
                $umidErr = "Must be numeric and exactly 8 characters long";
                $readyForSubmission = false;
            }
        }

        if (empty($_POST["firstName"])) {
            $firstNameErr = "First Name is required";
            $readyForSubmission = false;
        } 
        else {
            $firstName = test_input($_POST["firstName"]);
            // check if first name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$firstName)) {
                $firstNameErr = "Only letters and white space allowed";
                $readyForSubmission = false;
            }
        }

        if (empty($_POST["lastName"])) {
            $lastNameErr = "Last Name is required";
            $readyForSubmission = false;
        } 
        else {
            $lastName = test_input($_POST["lastName"]);
            // check if last name only contains letters and whitespace
            if (!preg_match("/^[a-zA-Z ]*$/",$lastName)) {
                $lastNameErr = "Only letters and white space allowed";
                $readyForSubmission = false;
            }
        }

        if (empty($_POST["projectTitle"])) {
            $projectTitleErr = "Project Title is required";
            $readyForSubmission = false;
        } 
        else {
            $projectTitle = test_input($_POST["projectTitle"]);
        }
    
        if (empty($_POST["email"])) {
            $emailErr = "Email is required";
            $readyForSubmission = false;
        } 
        else {
            $email = test_input($_POST["email"]);
            // check if e-mail address is well-formed
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $emailErr = "Invalid email format";
                $readyForSubmission = false;
            }
        }
        
        if (empty($_POST["phoneNumber"])) {
            $phoneNumber = "";
        } 
        else {
            $phoneNumber = test_input($_POST["phoneNumber"]);

            //checks if phone number follows 999-999-9999 format
            if (!preg_match("/^\d{3}-\d{3}-\d{4}$/", $phoneNumber)) {
                $phoneNumberErr = "Invalid phone number. Please follow correct format (i.e. 999-999-9999)";
                $readyForSubmission = false;
            }
        }

        if (empty($_POST["slotId"])) {
            $slotIdErr = "You must select a presentation slot";
            $readyForSubmission = false;
        } 
        else {
            $slotId = $_POST["slotId"];
        }

        if (!empty($_POST["resubmit"])) {
            $shouldResubmit = $_POST["slotId"];
        } 
        else {
            $slotId = $_POST["slotId"];
        }

        if(isset($_POST["resubmit"])) { 

            $_POST["resubmit"] = $_POST["resubmit"] == "true" ? true : false;
            $shouldResubmit = $_POST["resubmit"];
        }

        if ($readyForSubmission == true) {
            unset($presentSlot);
            foreach($presentSlots as $presentSlot) {
                if ($presentSlot['id'] == $slotId) {
                    $selectedDate = $presentSlot['date'];
                }
            }
            unset($presentSlot);

            $isIdUnique = CheckIdUnique($umid);

            //adds new registrant
            if ($isIdUnique == true) {
                $isIdUniqueErr = false;
                AddRegistrant($umid, $firstName, $lastName, $projectTitle, $email, $phoneNumber, $slotId, $selectedDate);

                $presentSlots = GetSlots();

                $isSubmitted = true;
            }
            else if ($shouldResubmit == true) {
                //changes registration date for students
                ChangeRegistration($umid, $firstName, $lastName, $projectTitle, $email, $phoneNumber, $slotId, $selectedDate);

                $presentSlots = GetSlots();

                $isSubmitted = true;
            }
            else {
                $isIdUniqueErr = true;
            }

        }
    }

    //cleans up submitted data
    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<!-- The display for the form. Once submitted the data is sent back to the same page, allowing
user submissions to be saved with ease as well as state of form submission. -->
<?php include 'nav.php'; ?>
<div class="header">
    <h1 style="text-align: center;">Presentation Signup</h1>
</div>
<div class="card">
    <div class="container">
    <?php if($isSubmitted == true) {echo "<h2>You have successfully registered!</h2>";} ?>
    <h2>Register Here</h2>
    <p><span class="error">* required field</span></p>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
    <?php if (isset($isIdUniqueErr) && $isIdUniqueErr == true) {
        echo '<span class="error">*It seems the UMID entered is already registered for
            a presentation. Please verify whether or not you
            would like to reschedule to this new date or retain the original slot.
            If you would like to reschedule, Please select yes and submit the form again</span><br>
            <input type="radio" name="resubmit" value="true">Yes, resubmit <br><br>'
            ;} ?>
    UMID: <input type="text" name="umid" value="<?php echo $umid;?>">
    <span class="error">* <?php echo $umidErr;?></span>
    <br><br>
    First Name: <input type="text" name="firstName" value="<?php echo $firstName;?>">
    <span class="error">* <?php echo $firstNameErr;?></span>
    <br><br>
    Last Name: <input type="text" name="lastName" value="<?php echo $lastName;?>">
    <span class="error">* <?php echo $lastNameErr;?></span>
    <br><br>
    Project Title: <input type="text" name="projectTitle" value="<?php echo $projectTitle;?>">
    <span class="error">* <?php echo $projectTitleErr;?></span>
    <br><br>
    E-mail: <input type="text" name="email" value="<?php echo $email;?>">
    <span class="error">* <?php echo $emailErr;?></span>
    <br><br>
    Phone Number: <input type="text" name="phoneNumber" value="<?php echo $phoneNumber;?>">
    <span class="error"><?php echo $phoneNumberErr;?></span>
    <br><br>
    Presentation Time Slot: <span class="error">* <?php echo $slotIdErr;?></span> <br>
    <?php foreach ($presentSlots as $presentSlot) : ?>
        <input type="radio" name="slotId" 
        <?php if ($slotId == $presentSlot['id']) echo "checked";?>
        <?php if ($presentSlot['slots_left'] == 0) echo "disabled";?>
        value="<?php echo $presentSlot['id'];?>">
        <?php echo $presentSlot['date'] . ", " . $presentSlot['slots_left'] . " seats left"?><br>
    <?php endforeach; ?>
    <br><br>
    <input type="submit" name="submit" value="Submit">  
    </form>
    </div>
</div>
</body>
</html>