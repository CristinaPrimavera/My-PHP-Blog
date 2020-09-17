<?php


class MyCategory
{

    /**
     * Get all the articles
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the article records
     */
    public static function getAllCategories($conn) {
        $sql = "SELECT *
                  FROM category
              ORDER BY name";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Get the article's categories
     *
     * @param object $conn Connection to the database
     * @param integer $id of the article
     *
     * @return array The category data
    */
    public static function getArticleCategories($conn, $id) {

        $sql = "SELECT category.*
                FROM category
                JOIN article_category
                ON category.id = article_category.category_id
                WHERE article_id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
