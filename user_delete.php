<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

$user = read_get_id('user-id');

if (!$user)
    redirect_back();

if (!user_is_authorized($user['UserId'], AUTH_USER_DELETE))
    redirect_login();
?>

<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)">
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
            $.post('api/user_delete.php', {'user-id': <?php echo $_GET['user-id']; ?>}, function () {
                window.location = '/user_admin.php';
            });
        }

        function clickNo() {
            window.location = '/user_admin.php';
        }
    </script>

<?php include_once 'footer.php'; ?>