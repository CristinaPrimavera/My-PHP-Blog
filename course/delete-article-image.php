<?php

require "../classes/MyArticles.php";
require "../classes/CrisDatabase.php";
require "../includes/validation.php";
require "../classes/Auth.php";

session_start();
Auth::requireLogin();

$db = new CrisDatabase();
$conn = $db->getConn();

if (isset($_GET['id'])) {

    $article = MyArticles::getByID($conn, $_GET['id']);

    if ($article) {

        $id = $article['id'];
        $images_file = $article['images_file'];

    } else {
        die("article not found");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (MyArticles::setImageFile($conn, $id, null)) {

        if ($images_file) {
            unlink("../uploads/$images_file");  //for deleting the old image in the files when uploading a new one
        }

        header("Location: article.php?id=$id");
    }


}

if ($images_file) {
    $tableImage = '<img src="../uploads/' . $images_file . '" height="300">';
}



require "../includes/my-header.php";?>

<h2>Delete article image:</h2>
<?= $tableImage; ?>

<?php require "../includes/my-footer.html"?>

<form method="post">
    <p>Are you sure?</p>

    <button>Delete image</button>
</form>



<footer>
    <br>
    <br>
    <nav class="nav">
        <a class="nav-link" href="article.php?id=<?=$_GET['id']?>">Back to Article</a>
    </nav>
</footer>
</html>
