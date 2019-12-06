<!DOCTYPE HTML>  
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Presentation Registration</title>
<link rel="stylesheet" href="styles/style.css">
</head>
<body>

<!-- This file displays a table of all students currently registered to present -->
<?php 
    include 'nav.php';
    include_once 'data.php';

    $registeredSlots = GetRegistrants();
?>

    <div class="header">
        <h1 style="text-align: center;">Presentation Registrants</h1>
    </div>
    <div class="table_card">
        <table id="registrants">
            <tr>
                <th>UMID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Project Title</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Presentation Date</th>
            </tr>

            <?php foreach ($registeredSlots as $registeredSlot) : ?>
            <tr>
                <td><?php echo $registeredSlot['umid']; ?></td>
                <td><?php echo $registeredSlot['first_name']; ?></td>
                <td><?php echo $registeredSlot['last_name']; ?></td>
                <td><?php echo $registeredSlot['project_title']; ?></td>
                <td><?php echo $registeredSlot['email']; ?></td>
                <td><?php echo $registeredSlot['phone_number']; ?></td>
                <td><?php echo $registeredSlot['presentation_date']; ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

</body>
</html>