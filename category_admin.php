<?php include_once 'header.php'; ?>

<?php
include_once 'includes/db.php';

if ($active_user['AcctType'] == 'ADMIN')
    $categories = db_connect_query('SELECT * FROM categories');
else
    $categories = db_connect_query('SELECT C.* FROM categories C, permissions P WHERE C.CategoryId=P.CategoryId AND P.UserId=?', $active_user['UserId']);
?>

<div id="page-content-container">
    <div id="page-content">
        <h1>Categories</h1>
        <table>
            <thead>
            <tr>
                <th>Label</th>
                <th>Description</th>
                <th>Curators</th>
                <th><!-- Actions --></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $categories->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Label']; ?></td>
                    <td><?php echo empty($row['Description']) ? '<i style="color: #ccc">No description...</i>' : $row['Description']; ?></td>
                    <td>
                        <?php
                        $curators = db_connect_query('SELECT U.* FROM users U, permissions P WHERE U.UserId=P.UserId AND P.CategoryId=?', $row['CategoryId']);
                        while ($user = $curators->fetch_assoc()): ?>
                            <a href="/user_edit.php?user-id=<?php echo $user['UserId']; ?>"><?php echo $user['Login']; ?></a>&nbsp;
                        <?php endwhile; ?>
                    </td>
                    <td style="text-align: right">
                        <a href="gallery_category.php?category-id=<?php echo $row['CategoryId']; ?>">View</a>
                        &nbsp;
                        <a href="category_edit.php?category-id=<?php echo $row['CategoryId']; ?>">Edit</a>
                        &nbsp;
                        <a href="category_delete.php?category-id=<?php echo $row['CategoryId']; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a class="link-add" href="/category_create.php">+ Add category</a>
    </div>
</div>

<?php include_once 'footer.php'; ?>
