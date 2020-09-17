<?php

require "../classes/MyArticles.php";
require "../classes/CrisDatabase.php";
require "../includes/validation.php";
require "../classes/Auth.php";
require "../classes/MyCategory.php";

session_start();
Auth::requireLogin();

$db = new CrisDatabase();
$conn = $db ->getConn();

if (isset($_GET['id'])) {

    $article = MyArticles::getWithCategories($conn, $_GET['id']);

    if ($article) {

        $title = $article[0]['title'];
        $content = $article[0]['content'];
        $published_at = $article[0]['published_at'];
        $id = $article[0]['id'];
        $category_names = array_column($article, 'category_name');
//        var_dump($category_names);

    } else {
        die("article not found");
    }

//    var_dump($article);
//    var_dump($title);
//    var_dump($id);
}


$inputCategories = '';
$categories = MyCategory::getAllCategories($conn);
foreach ($categories as $category) {
    $selectedAtr = '';
    if (in_array($category['name'], $category_names)) {
        $selectedAtr = 'selected';
    }

    $inputCategories .= '<option value="'.$category['id'].'" '.$selectedAtr.' >'.$category['name'].'</option>';
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $published_at = $_POST['published_at'];
    $category_ids = $_POST['category'] ?? [];  //null coalescing operator!

    $errors = validate($title, $content, $published_at);

    if (empty($errors)) {
        if (MyArticles::updateArticle($conn, $id, $title, $content, $published_at)) {

            MyArticles::setCategories($conn, $id, $category_ids);

            header("Location: article.php?id=$id");
        }
    } else {
        $errorString = "";
        foreach ($errors as $error) {
            $errorString .= '<li>'.$error.'</li>';
        }
    }
}



require "../includes/my-header.php";?>

<h2>Edit article:</h2>
<ul><?= $errorString ?></ul>

<form method="post">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" name="title" id="title" value="<?=htmlspecialchars($title);?>">
    </div>
    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" name="content" id="content" rows="4" cols="40"><?=htmlspecialchars($content);?></textarea>
    </div>
    <div class="form-group">
        <label for="published_at">Publication date and time</label>
        <input class="form-control" type="datetime-local" id="published_at" name="published_at" value="<?=htmlspecialchars($published_at);?>">
    </div>

    <div>
    <label for="category">Categories:</label>
    <select class="custom-select" name="category[]" id="category" multiple>
        <?=$inputCategories?>
    </select>
    </div>
    <br>
    <button>Save</button>
</form>

</main>
<!--</div>-->
</body>
<footer>
    <br>
    <nav class="nav">
        <a class="nav-link" href="article.php?id=<?=$_GET['id']?>">Back to Article</a>
    </nav>
</footer>
</html>
