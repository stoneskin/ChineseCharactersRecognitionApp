<?php
require "_header.php";
require_once '_incFunctions.php';
?>
  <div class="two-column-frame container">
            <div class="row">
<h2> Something is wrong.. </h2>
<?php
    if (isset($_GET['error'])) {
        $error = sanitizeHTML($_GET['error']);
        echo "<h3 style='color: red;'>$error</3>";
    }
   ?>
   </div>
</div>
<?php require "_footer.php"; ?>