<?php
include '../includes/db.php';
include '../includes/utils.php';



list($params, $num_empty) = safe_post_read('category-id', 'label?', 'description?');

if ($num_empty > 0)
    api_response(false, ERR_NO_CATEGORYID);

list($query, $values) = create_update_query('categories',
    array('Label', 'Description'),
    array($params['label'], $params['description']));

if (!empty($query)) {
    // Append WHERE clause to query
    $query .= 'WHERE CategoryId=?';
    $values[] = $params['category-id'];

    db_connect_query($query, $values);
}

api_response(true);