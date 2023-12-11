<?php
require "_header.php";
require_once '_incFunctions.php';
?>
<p> something is wrong <p>
<?php
    if (isset($_GET['error'])) {
        $error = sanitizeHTML($_GET['error']);
        echo "<p style='color: red;'>$error</p>";
    }
   ?>
<?php require "_footer.php"; ?>