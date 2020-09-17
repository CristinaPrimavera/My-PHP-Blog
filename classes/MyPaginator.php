<?php


class MyPaginator
{

    public $limit, $offset, $previous, $next, $total_pages;

    /**
     * MyPaginator constructor.
     *
     * @param integer $page Page number
     * @param integer $records_per_page Number of records/articles per page
     * @param integer $total_records Total number of records
     */
    public function __construct($page, $records_per_page, $total_records)
    {
        $this->limit = $records_per_page;

        $page = filter_var($page, FILTER_VALIDATE_INT, [
            'options' => [
                'default' => 1,
                'min_range' => 1
            ]
        ]);

        if ($page > 1) {
            $this->previous = $page - 1;
        }

        $this->total_pages = ceil($total_records / $records_per_page);
        if ($page < $this->total_pages) {
            $this->next = $page + 1;
        }

        $this->offset = $records_per_page * ($page -1);
    }


}