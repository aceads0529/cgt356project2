<?php
include_once 'includes/utils.php';
include_once 'includes/category.php';
include_once 'includes/image.php';
include_once 'includes/user.php';

$page_index = isset($_GET['page']) ? $_GET['page'] : 0;


$result = db_connect_query('SELECT * FROM images');
$images = [];

while ($row = $result->fetch_assoc())
    $images[] = $row;

$min = max(0, min(count($images), $page_index * 8));
$max = max(0, min(count($images), ($page_index + 1) * 8));

$can_edit = get_active_user()['AcctType'] == 'ADMIN';
$categories = get_all_categories();
?>
<?php include_once 'header.php'; ?>

<div id="page-content-container">
    <div id="page-content" style="text-align: center">
        <h1 style="font-size: 5em">Gallery</h1>
        <div class="description">View all images here, or filter by category below</div>
        <div class="gallery-filter">
            <?php foreach ($categories as $cat): ?>
                <a href="/gallery_category.php?category-id=<?php echo $cat['CategoryId']; ?>"><?php echo $cat['Label']; ?></a>
            <?php endforeach; ?>
        </div>

        <div class="image-nav">
            <div>
                <?php if ($page_index > 0): ?>
                    <a href="/gallery_all.php?page=<?php echo $page_index - 1; ?>">< Last page</a>
                <?php endif; ?>
            </div>
            <div class="range">
                <?php
                $max_page = count($images) / 8;
                for ($i = 0; $i < $max_page; $i++): ?>
                    <a <?php if ($i == $page_index) echo 'class="active"'; ?>
                            href="gallery_all.php?page=<?php echo $i; ?>"><?php echo $i + 1; ?></a>&nbsp;
                <?php endfor; ?>
            </div>
            <div>
                <?php if ($max != count($images)): ?>
                    <a href="/gallery_all.php?page=<?php echo $page_index + 1; ?>">Next page ></a>
                <?php endif; ?>
            </div>
        </div>

        <?php for ($i = $min; $i < $max; $i++): $img = $images[$i]; ?>
            <div class="image-thumb">
                <img src="<?php echo get_img_thumb_path($img); ?>" width="<?php echo 200 * $img['AspectRatio']; ?>"
                     height="200" data-src-large="<?php echo get_img_large_path($img); ?>"/>
                <div class="label">
                    <b><?php echo $img['Label']; ?></b>
                    <?php if ($can_edit): ?>
                        <span style="margin-left: 20px">
                            <a href="/image_edit.php?image-id=<?php echo $img['ImageId']; ?>">Edit</a>
                            &nbsp;
                            <a href="/image_delete.php?image-id=<?php echo $img['ImageId']; ?>">Delete</a>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endfor; ?>

        <div class="image-nav">
            <div>
                <?php if ($page_index > 0): ?>
                    <a href="/gallery_all.php?page=<?php echo $page_index - 1; ?>">< Last page</a>
                <?php endif; ?>
            </div>
            <div class="range">
                <?php
                $max_page = count($images) / 8;
                for ($i = 0; $i < $max_page; $i++): ?>
                    <a <?php if ($i == $page_index) echo 'class="active"'; ?>
                            href="gallery_all.php?page=<?php echo $i; ?>"><?php echo $i + 1; ?></a>&nbsp;
                <?php endfor; ?>
            </div>
            <div>
                <?php if ($max != count($images)): ?>
                    <a href="/gallery_all.php?page=<?php echo $page_index + 1; ?>">Next page ></a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="/scripts/lightbox.js"></script>
<?php include_once 'footer.php'; ?>
