<?php

require "../classes/CrisDatabase.php";
require "../classes/MyArticles.php";
require "../classes/Auth.php";
require "../includes/validation.php";
require "../classes/MyCategory.php";

session_start();
Auth::requireLogin();

$title = '';
$content = '';
$category_ids = [];

$db = new CrisDatabase();
$conn = $db->getConn();

$inputCategories = '';
$categories = MyCategory::getAllCategories($conn);
foreach ($categories as $category) {

    $inputCategories .= '<option value="'.$category['id'].'">'.$category['name'].'</option>';

}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = $_POST['title'];
    $content = $_POST['content'];
    $published_at = $_POST['published_at'];
    $category_ids = $_POST['category'] ?? [];  //null coalescing operator!

    $errors = validate($title, $content, $published_at);

    if (empty($errors)) {
        list($insertResult, $id) = MyArticles::newArticle($conn, $title, $content, $published_at);    //useful when you know exactly how big your array is!

        if ($insertResult) {

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


?>


<?php require "../includes/my-header.php";?>
<h2>New article:</h2>
<ul><?= $errorString ?></ul>
<form method="post">
    <div class="form-group">
        <label for="title">Title</label>
        <input class="form-control" name="title" id="title" placeholder="Article title" value="<?=htmlspecialchars($title);?>">
    </div>

    <div class="form-group">
        <label for="content">Content</label>
        <textarea class="form-control" name="content" id="content" rows="4" cols="40"
                  placeholder="Article content"><?=htmlspecialchars($content);?></textarea>
    </div>
    <div class="form-group">
        <label for="published_at">Publication date and time</label>
        <input class="form-control" type="datetime-local" id="published_at" name="published_at">
    </div>

    <div>
        <label for="category">Categories:</label>
        <select class="custom-select" name="category[]" id="category" multiple>
            <?=$inputCategories?>
        </select>
    </div>
    <br>
    <button>Add</button>
</form>
</main>
<!--</div>-->
</body>
</html>
