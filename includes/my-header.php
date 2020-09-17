<?php

//*****Creating variables to process login links on the template
$loggedInMessage = '';
$notLoggedMessage = '';
if (Auth::isLoggedIn()) {

    $loggedInMessage .= '<a class="nav-link" href="../includes/logout.php">Log out</a>';

} else {
    $notLoggedMessage .= '<a class="nav-link" href="../includes/login.php">Log in</a>';
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>My blog</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
          crossorigin="anonymous">
<!--    <link rel="stylesheet" href="../my-css/jquery.datetimepicker.min.css">-->
    <link rel="stylesheet" href="../my-css/my-style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>My blog</h1>
    </header>
    <nav>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link" href="../course/index.php">Home</a></li>
            <li class="nav-item"><?=$loggedInMessage?></li>
            <li class="nav-item"><?=$notLoggedMessage?></li>
        </ul>
    </nav>
    <br>
    <main>
