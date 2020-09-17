<?php

require "../classes/MyUsers.php";
require "../classes/CrisDatabase.php";
require "../classes/Auth.php";

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $db = new CrisDatabase();
    $conn = $db->getConn();

    if (MyUsers::authenticate($conn, $_POST['username'], $_POST['password'])) {

        Auth::login();

        header('Location: ../course/index.php');

    } else {

        $error = "Username or password incorrect";
    }
}

?>

<?php require "../includes/my-header.php";?>
<h2>Login</h2>
<p><?=$error?></p>

<form method="post">
    <div class="form-group">
        <label for="username">Username</label>
        <input name="username" id="username" class="form-control">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-control">
    </div>

    <button>Log in</button>
</form>
</main>
<!--</div>-->
</body>
</html>
