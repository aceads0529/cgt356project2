<?php
if (!isset($_GET['user-id'])) {
    header('Location: index.php');
    exit;
}

include_once 'includes/user.php';

$user = get_user_by_id($_GET['user-id']);

if (!$user) {
    header('Location: index.php');
    exit;
}

$user = $user->fetch_assoc();

?>

<?php include 'header.php'; ?>

    <div class="form-container">
        <form id="form-create" onsubmit="onSubmit(event)">
            <div class="title">Delete Account</div>
            <p>Are you sure you want to delete '<?php echo $user['Login']; ?>' permanently?</p>

            <div style="text-align: right; margin-top: 40px">
                <button onclick="clickNo()">No</button>
                <button onclick="clickYes()">Yes</button>
            </div>
        </form>
    </div>

    <script>
        function onSubmit(event) {
            event.preventDefault();
        }

        function clickYes() {
            $.post('api/user_delete.php', {'user-id': <?php echo $_GET['user-id']; ?>}, function (result) {
                window.location = '/user_admin.php';
            });
        }

        function clickNo() {
            window.location = '/user_admin.php';
        }
    </script>

<?php include 'footer.php'; ?>