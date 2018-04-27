<?php include_once 'header.php'; ?>

<?php
include_once 'includes/db.php';

$db = db_connect();

$admins = db_query($db, 'SELECT * FROM users WHERE AcctType="ADMIN"');
$curators = db_query($db, 'SELECT * FROM users WHERE AcctType="CURATOR"');
?>

<div id="page-content-container">
    <div id="page-content">
        <h1>Accounts</h1>
        <h2>Admins</h2>
        <table>
            <thead>
            <tr>
                <th>Username</th>
                <th>First name</th>
                <th>Last name</th>
                <th><!-- Permissions --></th>
                <th><!-- Actions --></th>
            </tr>
            </thead>
            <tbody>
            <?php while ($row = $admins->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['Login']; ?></td>
                    <td><?php echo $row['FirstName']; ?></td>
                    <td><?php echo $row['LastName']; ?></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
        <a class="link-add" href="/user_create.php?acct-type=ADMIN">+ Add admin</a>

        <h2>Curators</h2>
        <?php if ($curators->num_rows > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>Username</th>
                    <th>First name</th>
                    <th>Last name</th>
                    <th>Permissions</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php while ($row = $curators->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['Login']; ?></td>
                        <td><?php echo $row['FirstName']; ?></td>
                        <td><?php echo $row['LastName']; ?></td>
                        <td>
                            <?php
                            $cats = get_user_categories($row['UserId']);
                            for ($i = 0; $i < count($cats); $i++) {
                                echo $cats[$i]['Label'];
                                if ($i != count($cats) - 1)
                                    echo ', ';
                            }
                            ?>
                        </td>
                        <td style="text-align: right">
                            <a href="/user_edit.php?user-id=<?php echo $row['UserId']; ?>">Edit</a>
                            &nbsp;
                            <a href="/user_delete.php?user-id=<?php echo $row['UserId']; ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><i>There are no curator accounts.</i></p>
        <?php endif; ?>
        <a class="link-add" href="/user_create.php?acct-type=CURATOR">+ Add curator</a>
    </div>
</div>

<?php include_once 'footer.php'; ?>
