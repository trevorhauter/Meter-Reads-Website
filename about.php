<?php 

    session_start();

    if (isset($_GET['restart'])){
        session_destroy();
        session_start();
    }

    include "functions.php";

    if (!isset($_GET['page'])){
        $_GET['id'] = 'about';
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meter Reads</title>

    <?php include "refs.php"; ?>
    
</head>
<body>
   
<?php include "header.php"; ?>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-2">

        </div>

        <div class="col-lg-8">
            <center>
                <h2>What We Do</h2>
                <hr>
                <h3 class="aboutHeaders">Utility Billing</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
            </center>

            <div class="row">

                <div class="col-lg-6">
                    <center>
                        <h3 class="aboutHeaders">Usage Reports</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                    </center>
                </div>

                <div class="col-lg-6">
                    <center>
                        <h3 class="aboutHeaders">Meter Reads</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur</p>
                    </center>
                </div>

            </div>

        </div>

        <div class="col-lg-2">

        </div>

    </div>

    <?php include "footer.php"; ?>

</div>

</body>
</html>