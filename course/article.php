<?php

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require "../classes/CrisDatabase.php";
require "../classes/MyArticles.php";
require "../classes/Auth.php";

session_start();

$db = new CrisDatabase();
$conn = $db->getConn();

if (isset($_GET['id'])) {

    $table_array = MyArticles::getWithCategories($conn, $_GET['id']);

} else {

    $table_array = null;
}

//var_dump($table_array);

$title = $table_array[0]['title'];
$content = $table_array[0]['content'];
$images_file = $table_array[0]['images_file'];
$tableCategories = implode(', ', array_column($table_array, 'category_name'));

$falseTable = '';
if ($table_array) {
    $tableInfo = '<h4>' . htmlspecialchars($title) . '</h4>
                   <p class="show-categories">Categories: ' . htmlspecialchars($tableCategories) . '</p>
                   <p>' . htmlspecialchars($content) . '</p>';

    if ($images_file) {
        $tableImage = '<img src="../uploads/'.$images_file.'" height="300">';
    }


} else {
    $falseTable = '<p>Article not found</p>';
}




require "../includes/my-header.php";?>

<h2>Blog article:</h2>
<br>
<?= $tableInfo; ?>
<?= $tableImage; ?>
<?= $falseTable; ?>

</main>
<!--</div>-->
</body>
<footer>
    <br>
    <nav class="nav flex-column">
        <a class="nav-link" href="edit-article.php?id=<?=$_GET['id']?>">Edit article</a>
        <a class="nav-link" href="edit-article-image.php?id=<?=$_GET['id']?>">Edit image</a>
        <a class="nav-link delete" href="delete-article.php?id=<?=$_GET['id']?>">Delete article</a>
    </nav>

    <?php require "../includes/my-footer.html"?>
</footer>
</html>

