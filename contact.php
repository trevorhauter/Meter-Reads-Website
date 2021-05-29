<?php 

    session_start();

    if (isset($_GET['restart'])){
        session_destroy();
        session_start();
    }

    include "functions.php";

    if (!isset($_GET['page'])){
        $_GET['id'] = 'contact';
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
                <h2>Contact Us</h2>
            </center>
            <hr>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-lg-2">

        </div>

        <div class="col-lg-3">
            <form>
              <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
              </div>
              <br>
              <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
              </div>
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
              </div>
              <br>
              <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <div class="col-lg-1">

        </div>

        <div class="col-lg-4">
            <h3>Let's see how we can help.</h3>
            <p>RealMTRX is equipped to work with real estate portfolios of all different sizes.  Contact us for a free demo to see exactly how we can help streamline your business and make your life easier.</p>
            <h3>RealMTRX</h3>
            <p>534 Johnnie Dodds Boulevard, Mount Pleasant, South Carolina 29464, United States</p>
        </div>

        <div class="col-lg-2">

        </div>

    </div>

    <?php include "footer.php"; ?>
</div>
</body>
</html>