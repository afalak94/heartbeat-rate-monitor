<?php 
if(session_id() == '' || !isset($_SESSION)) {
    // session isn't started
    session_start();
} ?>
<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <script src="//d3js.org/d3.v3.min.js"></script>

    <title>Heartbeat Rate Monitor</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
<!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" style="margin-bottom: 50px;">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php">Heartbeat Rate Monitor</a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <?php if (!isset($_SESSION['user_name'])):
                    echo '<li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>';
                    endif; ?>
                </ul>
                <div class="nav-login" style="padding-top: 12px; float: right;">

                    <?php
                        if (isset($_SESSION['user_name'])) {
                            echo '

                                <form action="includes/logout.inc.php" method="POST" style="float: right;">
                                    <button type="submit" name="submit"><span class="glyphicon glyphicon-log-in"></span> ' . $_SESSION['user_name'] . ' Logout</button>
                                </form>';
                        }
                        else {
                            echo '<form action="includes/login.inc.php" method="POST">
                                <input type="text" name="user_name" placeholder="Username">
                                <input type="password" name="password" placeholder="password">
                                <button type="submit" name="submit"><span class="glyphicon glyphicon-log-in"></span> Login</button>
                                </form>';
                        }
                    ?>

                </div>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>