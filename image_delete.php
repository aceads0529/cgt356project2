<?php
include_once 'includes/utils.php';

$image = get_from_id('image-id');

if (!$image) {
    header('Location: /index.php');
    exit;
}
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
            $.post('api/image_delete.php', {'image-id': <?php echo $_GET['image-id']; ?>}, function (result) {
                window.location = '/gallery_category.php?category-id=<?php echo $image['CategoryId']; ?>';
            });
        }

        function clickNo() {
            window.location = '/gallery_category.php?category-id=<?php echo $image['CategoryId']; ?>';
        }
    </script>

<?php include_once 'footer.php'; ?>