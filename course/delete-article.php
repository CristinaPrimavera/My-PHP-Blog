<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require "../classes/CrisDatabase.php";
require "../classes/MyArticles.php";
require "../classes/Auth.php";

session_start();
Auth::requireLogin();

$db = new CrisDatabase();
$conn = $db->getConn();

if (isset($_GET['id'])) {

    $article = MyArticles::getByID($conn, $_GET['id']);

    if ($article) {

        $id = $article['id'];

    } else {
        echo "article not found";
    }
}

if (isset($_POST['id']) && isset($_POST['function']) && $_POST['function'] == 'delete') {    //Probando el uso/practicidad de tener hidden inputs!

    if (MyArticles::deleteArticle($conn, $id)) {

        header("Location: index.php");

    }
}



require "../includes/my-header.php";?>

<h2>Delete article:</h2>

<form method="post">
    <input type="hidden" name="id" value="<?=$_GET['id']?>">
    <input type="hidden" name="function" value="delete">
    <p>Are you sure?</p>

    <button>Delete the article</button>
</form>

</main>
<!--</div>-->
</body>
<footer>
    <br>
    <nav class="nav">
        <a class="nav-link" href="article.php?id=<?=$_GET['id']?>">Back to Article</a>
    </nav>

    <?php require "../includes/my-footer.html"?>
</footer>
</html>
