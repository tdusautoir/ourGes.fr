<?php if (isset_flash_message_by_type(FLASH_SUCCESS)) : ?>
    <div class="notif green">
        <i class="fa fa-circle-check"></i>
        <p><?php display_flash_message_by_type(FLASH_SUCCESS); ?></p>
    </div>
<?php elseif (isset_flash_message_by_type(FLASH_ERROR)) : ?>
    <div class="notif red">
        <i class="fa fa-cirlce-xmark"></i>
        <p><?php display_flash_message_by_type(FLASH_ERROR); ?></p>
    </div>
<?php endif; ?>
<script>
    notif();
</script>