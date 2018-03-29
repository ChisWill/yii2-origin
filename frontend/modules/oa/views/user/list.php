<?php common\assets\HighLightAsset::register($this) ?>
<style>
i.tips {
    background: #f31;
    border-radius: 50%;
    display: block;
    height: 8px;
    position: absolute;
    right: 22px;
    top: 8px;
    width: 8px;
}
</style>

<?= $html ?>

<script>
$(function () {
    'info-fancybox'.config({
        minWidth: 500,
        maxHeight: 500
    });

    $(".list-container table").on('click', 'td .info-fancybox', function () {
        if ($(this).parent().find('.tips').length > 0) {
            $(this).parent().find('.tips').remove();
        }
    });
});
</script>