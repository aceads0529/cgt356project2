<?php
include_once 'db.php';
include_once 'utils.php';

/**
 * Generates an empty user array
 *
 * @return array
 */
function user_empty()
{
    return [
        'UserId' => -1,
        'AcctType' => '',
        'Login' => '',
        'PswdHash' => '',
        'PswdSalt' => '',
        'FirstName' => '',
        'LastName' => ''
    ];
}

/**
 * Returns whether a username already exists in the database
 *
 * @param mysqli $db
 * @param string $username
 * @return bool
 */
function user_exists($db, $username)
{
    return db_query($db, 'SELECT * FROM users WHERE Login=?', $username)->num_rows > 0;
}

/**
 * Returns the active user ID, or false if no user is logged in
 *
 * @return int|bool
 */
function get_active_user_id()
{
    safe_session_start();
    return isset($_SESSION['user-id']) ? $_SESSION['user-id'] : false;
}

/**
 * Returns the active user, or false if no user is logged in
 *
 * @return array|bool
 */
function get_active_user()
{
    if ($userId = get_active_user_id()) {
        return get_user_by_id($userId)->fetch_assoc();
    } else {
        return false;
    }
}

/**
 * Return user from ID, of false if not found
 *
 * @param int $userId
 * @return mysqli_result|bool
 */
function get_user_by_id($userId)
{
    if (empty($userId))
        return false;

    return db_connect_query('SELECT * FROM users WHERE UserId=?', $userId);
}

/**
 * Return array of all categories owned by user
 *
 * @param int $userId
 * @return array
 */
function get_user_categories($userId)
{
    $db = db_connect();
    $query = db_query($db, 'SELECT CategoryId FROM permissions WHERE UserId=?', $userId);
    $result = [];

    while ($row = $query->fetch_assoc()) {
        $result[] = db_query($db, 'SELECT * FROM categories WHERE CategoryId=?', $row['CategoryId'])->fetch_assoc();
    }

    return $result;
}

/**
 * Adds permission to categories from array, and removes all others
 *
 * @param int $userId
 * @param array $categories
 */
function set_user_categories($userId, $categories)
{
    $user = get_user_by_id($userId)->fetch_assoc();

    if ($user['AcctType'] != 'CURATOR')
        return;

    if (empty($categories))
        $categories = [];

    foreach ($categories as $categoryId => $value) {
        if ($value == 'true')
            add_user_category($userId, $categoryId);
        else
            remove_user_category($userId, $categoryId);
    }
}

/**
 * Adds ownership of a category to a user
 *
 * @param int $userId
 * @param int $categoryId
 */
function add_user_category($userId, $categoryId)
{
    if (!user_has_category($userId, $categoryId))
        db_connect_query('INSERT INTO permissions (UserId, CategoryId) VALUES (?, ?)', $userId, $categoryId);
}

/**
 * Removes ownership of a category from a user
 *
 * @param int $userId
 * @param int $categoryId
 */
function remove_user_category($userId, $categoryId)
{
    db_connect_query('DELETE FROM permissions WHERE UserId=? AND CategoryId=?', $userId, $categoryId);
}

/**
 * Returns whether a user has permission to a certain category
 *
 * @param string $userId
 * @param string $categoryId
 * @return bool
 */
function user_has_category($userId, $categoryId)
{
    return db_connect_query('SELECT * FROM permissions WHERE UserId=? AND CategoryId=?', $userId, $categoryId)->num_rows > 0;
}

define('AUTH_USER_CREATE', 0);
define('AUTH_USER_EDIT', 1);
define('AUTH_USER_DELETE', 2);
define('AUTH_CATEGORY_CREATE', 3);
define('AUTH_CATEGORY_EDIT', 4);
define('AUTH_CATEGORY_DELETE', 5);
define('AUTH_IMAGE_CREATE', 6);
define('AUTH_IMAGE_EDIT', 7);

/**
 * Returns whether a user is authorized to perform an action
 *
 * @param mixed $context
 * @param int $auth_mode
 * @return bool
 */
function user_is_authorized($context, $auth_mode)
{
    $user = get_active_user();

    if (!$user)
        return false;

    switch ($auth_mode) {
        case AUTH_USER_CREATE:
            return $user['AcctType'] == 'ADMIN';
        case AUTH_USER_EDIT:
        case AUTH_USER_DELETE:
            return get_user_by_id($context)->fetch_assoc()['AcctType'] != 'ADMIN' && ($user['AcctType'] == 'ADMIN' || $user['UserId'] == $context);

        case AUTH_CATEGORY_CREATE:
        case AUTH_CATEGORY_DELETE:
            return $user['AcctType'] == 'ADMIN';
        case AUTH_CATEGORY_EDIT:
            return $user['AcctType'] == 'ADMIN' || user_has_category($user['UserId'], $context);

        case AUTH_IMAGE_CREATE:
        case AUTH_IMAGE_EDIT:
            return $user['AcctType'] == 'ADMIN';
    }
}