<?php

require "../classes/MyArticles.php";
require "../classes/CrisDatabase.php";
require "../includes/validation.php";
require "../classes/Auth.php";

session_start();
Auth::requireLogin();

$db = new CrisDatabase();
$conn = $db ->getConn();

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
    var_dump($_FILES);

    try {
        if (empty($_FILES)) {
            throw new Exception('Invalid upload');
        }

        switch ($_FILES['file']['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new Exception('No file selected');
                break;
            case UPLOAD_ERR_INI_SIZE:
                throw new Exception('File is too large');
                break;
            default:
                throw new Exception('An error occurred');
        }


        //******Restrict type of uploaded file
        $mime_types = ['image/gif', 'image/png', 'image/jpeg'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES['file']['tmp_name']);
        if (! in_array($mime_type, $mime_types)) {
            throw new Exception('Invalid file type');
        }

        //******Move uploaded file
        $pathinfo = pathinfo($_FILES['file']['name']);
        $base = $pathinfo['filename'];

        $base = preg_replace('/[^a-zA-Z0-9_-]/', '_', $base);
        $base = mb_substr($base, 0, 200);

        $filename = $base . "." . $pathinfo['extension'];
        $destination = "../uploads/$filename";

        $i = 1;
        while (file_exists($destination)) {

            $filename = $base . "-$i" . $pathinfo['extension'];
            $destination = "../uploads/$filename";
            $i++;
        }

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {

            if (MyArticles::setImageFile($conn, $id, $filename)) {

                if ($images_file) {
                    unlink("../uploads/$images_file");  //for deleting the old image in the files when uploading a new one
                }

                header("Location: article.php?id=$id");
            }

        } else {
            throw new Exception('Unable to move uploaded file');
        }


    } catch (Exception $e) {
        $error = $e->getMessage();
    }

}

if ($images_file) {
    $showImage = '<img src="../uploads/'.$images_file.'" height="300">';
    $deleteImage = '<a class="nav-link delete" href="delete-article-image.php?id='.$_GET['id'].'">Delete image</a>';
}




require "../includes/my-header.php";?>

<h2>Edit article image:</h2>
<?= $showImage; ?>
<br>
<br>
<?=$error?>

<form method="post" enctype="multipart/form-data">
    <div class="form-group">
        <br>
        <label for="file">Image file</label>
        <input class="form-group" type="file" name="file" id="file">
    </div>
    <button>Upload</button>
</form>

</main>
<!--</div>-->
</body>
<footer>
    <br>
    <br>
    <nav class="nav flex-column">
        <?=$deleteImage?>
        <a class="nav-link" href="article.php?id=<?=$_GET['id']?>">Back to Article</a>
    </nav>

    <?php require "../includes/my-footer.html"?>
</footer>
</html>
