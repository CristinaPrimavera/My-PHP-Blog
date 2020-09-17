<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../classes/MyArticles.php";
require "../classes/CrisDatabase.php";
require "../classes/Auth.php";
require "../classes/MyPaginator.php";
require "../classes/MyCategory.php";


session_start();


//*****Getting the content from the database as an array
$db = new CrisDatabase();
$conn = $db->getConn();


$paginator = new MyPaginator(isset($_GET['page']) ? $_GET['page'] : 1, 6, MyArticles::getTotal($conn));
//OR: $paginator = new MyPaginator($_GET['page'] ?? 1, 6);  The null coalescing operator (??): for the common case of needing to use a ternary in conjunction with isset()!

$table_array = MyArticles::getPage($conn, $paginator->limit, $paginator->offset);

//var_dump($table_array);



//*****Creating a variable to display content in the template
$tableString = '';

foreach ($table_array as $table_content) {

    $categories = [];
    $categories = MyCategory::getArticleCategories($conn, $table_content['id']);
    $category_names = implode(', ', array_column($categories, 'name'));


    $tableString .= '<li><h4><a href="article.php?id=' . $table_content['id'] . '">'
                    . htmlspecialchars($table_content['title'])
                    . '</a></h4><p class="show-categories">Categories: '
                    . $category_names . '</p><p>'
                    . htmlspecialchars($table_content['content']) . '</p></li>';
}


//*****Creating variables to display pagination links on the template
$pageElement = isset($_GET['page']) ? $_GET['page'] : 1;
$pageCounter = '<a class="page-link" href="index.php?page='.$pageElement.'">'.$pageElement.'/'.$paginator->total_pages.'</a>';

$previousElement = '';
$nextElement = '';

if ($paginator->previous) {
    $previousElement .= '<a class="page-link" href="index.php?page='.$paginator->previous.'">Previous</a>';
} else {
    $previousElement .= '<a class="page-link" href="index.php?page='.$pageElement.'">Previous</a>';
}

if ($paginator->next) {
    $nextElement .= '<a class="page-link" href="index.php?page='.$paginator->next.'">Next</a>';
} else {
    $nextElement .= '<a class="page-link" href="index.php?page='.$pageElement.'">Next</a>';
}

?>



<?php require "../includes/my-header.php";?>

<ul id="index">
    <?= $tableString; ?>
</ul>

<br>
<nav aria-label="Search results pages">
    <ul class="pagination">
        <li class="page-item"><?=$previousElement?></li>
        <li class="page-item"><?=$pageCounter?></li>
        <li class="page-item"><?=$nextElement?></li>
    </ul>
</nav>

</main>
</body>
<footer>
    <nav class="nav flex-column">
        <a class="nav-link" href="../course/new-article.php">Insert a new article</a>
    </nav>
</footer>
</div>
</html>

