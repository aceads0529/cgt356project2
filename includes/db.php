<?php


/**
 * Connect to MySQL database
 *
 * @return mysqli
 */
function db_connect()
{
    $db = mysqli_connect('aaroneads.com:3306', 'admin', 'Ascii32', 'cgt356_project2');
    return $db;
}


/**
 * Close connection to MySQL database
 *
 * @param mysqli $db
 */
function db_close($db)
{
    mysqli_close($db);
}

/**
 * Queries MySQL database (using prepared statements)
 *
 * @param mysqli $db
 * @param string $query
 * @param array ...$args
 * @return bool|mysqli_result
 */
function db_query($db, $query, ...$args)
{
    $arg_types = '';

    foreach ($args as $a) {
        if (is_string($a))
            $arg_types .= 's';
        elseif (is_integer($a))
            $arg_types .= 'i';
        elseif (is_double($a))
            $arg_types .= 'd';
    }

    $params[] = &$arg_types;

    for ($i = 0; $i < count($args); $i++)
        $params[] = &$args[$i];

    $stmt = $db->prepare($query);
    call_user_func_array(array($stmt, 'bind_param'), $params);
    $stmt->execute();

    $result = empty($stmt->error) ? $stmt->get_result() : $stmt->error;
    $stmt->close();

    return $result;
}