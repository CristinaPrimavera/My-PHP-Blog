<?php

require "../classes/Auth.php";

session_start();

Auth::logout();

header('Location: ../course/index.php');