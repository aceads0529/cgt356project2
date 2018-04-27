<?php
include_once 'includes/db.php';
include_once 'includes/user.php';

if (!user_is_authorized(null, AUTH_CATEGORY_DELETE))
    redirect_login();

$category = read_get_id('category-id');

if (!$category)
    redirect_back();

if (!$category) {
    header('Location: index.php');
    exit;
}

$category = $category->fetch_assoc();

?>

<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)">
            <div class="title">Delete Account</div>
            <p>Are you sure you want to delete '<?php echo $category['Label']; ?>' permanently?</p>

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
            $.post('api/category_delete.php', {'category-id': <?php echo $_GET['category-id']; ?>}, function () {
                window.location = '/category_admin.php';
            });
        }

        function clickNo() {
            window.location = '/category_admin.php';
        }
    </script>

<?php include_once 'footer.php'; ?>