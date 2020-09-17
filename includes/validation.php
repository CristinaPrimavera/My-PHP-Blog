<?php

/**
 * Validate the article properties
 *
 * @param string $title Title, required
 * @param string $content Content, required
 * @param string $published_at Published date and time, dd.mm.yyyy hh:mm:ss if not blank
 *
 * @return array
 */
function validate($title, $content, $published_at) {

    $errors = [];

    if ($title == '') {
        $errors[] = 'Title is required';
    }
    if ($content == '') {
        $errors[] = 'Content is required';
    }

    if ($published_at != '') {

        $date_time = date_create_from_format('Y-m-d H:i:s', $published_at);

        if ($date_time === false) {
            $errors[] = 'Invalid date and time';
        } else {
            $date_errors = date_get_last_errors();

            if ($date_errors['warning_count'] > 0) {
                $errors[] = 'Invalid date and time';
            }
        }
    }

    return $errors;

}