<?php
include_once 'includes/utils.php';
include_once 'includes/user.php';

$image = read_get_id('image-id');

if (!$image)
    redirect_back();

if (!user_is_authorized($image['CategoryId'], AUTH_IMAGE_DELETE))
    redirect_login();
?>

<?php include_once 'header.php'; ?>

    <div class="form-container">
        <form onsubmit="onSubmit(event)">
            <div class="title">Delete Account</div>
            <p>Are you sure you want to delete '<?php echo $image['Label']; ?>' permanently?</p>

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
            $.post('api/image_delete.php', {'image-id': <?php echo $_GET['image-id']; ?>}, function () {
                window.location = '<?php echo get_back_url(); ?>';
            });
        }

        function clickNo() {
            window.location = '<?php echo get_back_url(); ?>';
        }
    </script>

<?php include_once 'footer.php'; ?>