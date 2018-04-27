<?php
include_once 'db.php';
include_once 'utils.php';

/**
 * Returns the active user, or false if no user is logged in
 *
 * @return array|bool
 */
function get_active_user()
{
    safe_session_start();
    $userId = isset($_SESSION['user-id']) ? $_SESSION['user-id'] : false;

    if ($userId) {
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
    $query = db_query($db, 'SELECT C.* FROM categories C, permissions P WHERE C.CategoryId=P.CategoryId AND P.UserId=?', $userId);
    $result = [];

    while ($row = $query->fetch_assoc()) {
        $result[] = $row;
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
    $active_user = get_active_user();
    $user = get_user_by_id($userId)->fetch_assoc();

    if ($user['AcctType'] != 'CURATOR' || !$active_user || $active_user['AcctType'] != 'ADMIN')
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
define('AUTH_IMAGE_DELETE', 7);

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
        case AUTH_CATEGORY_EDIT:
            return $user['AcctType'] == 'ADMIN';

        case AUTH_IMAGE_CREATE:
        case AUTH_IMAGE_EDIT:
        case AUTH_IMAGE_DELETE:
            return $user['AcctType'] == 'ADMIN' || user_has_category($user['UserId'], $context);
        default:
            return false;
    }
}
