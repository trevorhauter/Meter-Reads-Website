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
    <title>RealMTRX</title>

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
                <h3 class="aboutHeaders">Integrated Tenant Billing and Utility Processing</h3>
                <p>We process and store your utility bills on our secure, cloud-based architecture so you can have 24/7 access to utility analytics and downloadable copies of every bill.  We guarantee no late fees and our software works directly with your accounting system offering seamless communication between A/P and Operations.</p>
            </center>

            <div class="row">

                <div class="col-lg-6">
                    <center>
                        <h3 class="aboutHeaders">Billing Solutions</h3>
                        <p>We provide alternative utility billing solutions such as submetering and RUBS.  Fairly and accurately splitting up utility spend within your property saves you money and increases sustainability.</p>
                    </center>
                </div>

                <div class="col-lg-6">
                    <center>
                        <h3 class="aboutHeaders">Utility Counsel and Recommendations</h3>
                        <p>We can help future-proof your business by helping implement sustainability practices and environmental initiatives.  Our team of experienced consultants will ensure that you're using energy as efficiently as possible and recuperating all of your costs.</p>
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