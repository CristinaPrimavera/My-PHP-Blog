<?php


class MyArticles
{
//    /**
//     * Validation error messages
//     * @var array
//    */
//    private $errors = [];
//
//    /**
//     * @return array
//     */
//    public function getErrors()
//    {
//        return $this->errors;
//    }


    /**
     * Get the article record based on the ID along with the associated categories, if any
     * @param object $conn Connection to the database
     * @param integer $id of the article
     *
     * @return array The article data with categories
    */
    public static function getWithCategories($conn, $id) {

        $sql = "SELECT mycoursedata.*, category.name AS category_name
                  FROM mycoursedata
                    LEFT JOIN article_category ON mycoursedata.id = article_category.article_id
                    LEFT JOIN category ON category.id = article_category.category_id
                 WHERE mycoursedata.id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Get all the articles
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the article records
     */
    public static function getAll($conn) {
        $sql = "SELECT *
                  FROM mycoursedata";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     *Get a page of articles
     *
     *@param object $conn Connection to the database
     *@param integer $limit Number of records to return
     *@param integer $offset Number of records to skip
     *
     *@return array Assoc array of the page of article records
     */
    public static function getPage($conn, $limit, $offset) {

        $sql = "SELECT *
                  FROM mycoursedata
                 LIMIT :limit
                OFFSET :offset";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Get a count of the total number of records
     *
     * @param object $conn Connection to the database
     *
     * @return integer The total number of records
    */
    public static function getTotal($conn) {

        return $conn->query('SELECT COUNT(*) FROM mycoursedata')->fetchColumn();
    }


    /**
     * Get the article record based on the ID
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     *
     * @return mixed An associative array containing the article with that ID, or false if not found
     */
    public static function getByID($conn, $id)
    {

        $sql = "SELECT *
                  FROM mycoursedata
                 WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($stmt->execute()) {

            return $stmt->fetch(PDO::FETCH_ASSOC);

        }

    }


    /**
     * Update the article with its current property values
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     * @param string $title of the article
     * @param string $content of the article
     * @param string $published_at of the article
     *
     * @return boolean True if the update was successful, false otherwise
    */
    public static function updateArticle($conn, $id, $title, $content, $published_at) {

//        if (empty(validate($title, $content, $published_at))) {

            $sql = "UPDATE mycoursedata 
                       SET title = :title,
                       content = :content, 
                       published_at = :published_at
                     WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $content, PDO::PARAM_STR);

            if ($published_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $published_at, PDO::PARAM_STR);
            }

            return $stmt->execute();

//        } else {
//            return false;
//        }

    }


    /**
     * Set the article categories
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     * @param array $category_ids Category IDs
    */
    public static function setCategories($conn, $id, $category_ids) {

        if ($category_ids) {
            $sql = "INSERT INTO article_category (article_id, category_id)
                         VALUES ('$id', :category_id)";

            $stmt = $conn->prepare($sql);

            foreach ($category_ids as $category_id) {
                $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
                $stmt->execute();
            }

        }

        $sql = "DELETE FROM article_category
                WHERE article_id = '$id'";

        if ($category_ids) {

            $placeholders = array_fill(0, count($category_ids), '?');

            $sql .= "AND category_id NOT IN (" . implode(", ", $placeholders) . ")";
        }

        $stmt = $conn->prepare($sql);

        foreach ($category_ids as  $i => $category_id) {
            $stmt->bindValue($i + 1, $category_id, PDO::PARAM_INT);   //used "?" instead of a named placeholder.
//                                                                    //So, to bind the values, the "1-indexed" position of the parameter is needed

        }

        $stmt->execute();



        //A "more efficient" alternative (because single query to the database). Didn't work

//        if ($category_ids) {
//            $sql = "INSERT INTO article_category (article_id, category_id)
//                         VALUES ";
//
//            $values = [];
//            foreach ($category_ids as $category_id) {
//                $values[] = "({$id}, ?)";
//            }
//
//            $sql .= implode(', ', $values);
//
//            $stmt = $conn->prepare($sql);
//
//            foreach ($category_ids as  $i => $category_id) {
//                $stmt->bindValue($i + 1, $category_id, PDO::PARAM_INT);
//            }
//
//            $stmt->execute();
//        }

    }


    /**
     * Delete the current article
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     *
     * @return boolean True if the delete was successful, false otherwise
    */
    public static function deleteArticle($conn, $id)
    {
        $sql = "DELETE FROM mycoursedata 
                      WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }


    /**
     * Insert new article
     *
     * @param object $conn Connection to the database
     * @param string $title of the article
     * @param string $content of the article
     * @param string $published_at of the article
     *
     * @return array True and new generated Id if the update was successful, false and -1 otherwise
    */
    public static function newArticle($conn, $title, $content, $published_at) {

        $sql = "INSERT INTO mycoursedata (title, content, published_at)
                     VALUES (:title, :content, :published_at)";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':content', $content, PDO::PARAM_STR);

        if ($published_at == '') {
            $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':published_at', $published_at, PDO::PARAM_STR);
        }

        if ($stmt->execute()) {

            return [true, $conn->lastInsertId()];
        }
        return [false, -1];
    }


    /**
     * Update the image file
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     * @param string $filename of the image file
     *
     * @return boolean True if the update was successful, false otherwise
     *
    */
    public static function setImageFile($conn, $id, $filename) {

        $sql = "UPDATE mycoursedata
                   SET images_file = :images_file
                 WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':images_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }


//    /**
//     * Validate the article properties, putting any validation error messages in the $errors property
//     *
//     * @param string $title Title, required
//     * @param string $content Content, required
//     * @param string $published_at Published date and time, dd.mm.yyyy hh:mm:ss if not blank
//     *
//     * @return array
//    */
//    protected function validate($title, $content, $published_at) {
//
//        $errors = [];
//
//        if ($title == '') {
//            $this->errors[] = 'Title is required';
//        }
//        if ($content == '') {
//            $this->errors[] = 'Content is required';
//        }
//
//        if ($published_at != '') {
//            $date_time = date_create_from_format('d.m.Y H:i:s', $published_at);
//
//            if ($date_time === false) {
//                $this->errors[] = 'Invalid date and time';
//            } else {
//                $date_errors = date_get_last_errors();
//
//                if ($date_errors['warning_count'] > 0) {
//                    $this->errors[] = 'Invalid date and time';
//                }
//            }
//        }
//
//        return $errors;
//
//    }
}