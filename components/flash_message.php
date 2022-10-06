<?php if (isset_flash_message_by_type(FLASH_ERROR)) : ?>
    <div class="modal error gap-1 flex flex-al" id="flash">
        <i class="fa fa-cirlce-xmark"></i>
        <?php if (isset_flash_message_by_name(ERROR_LOGIN)) : ?>
            <p><?php display_flash_message_by_name(ERROR_LOGIN); ?></p>
        <?php elseif (isset_flash_message_by_name(ERROR_HANDLER)) : ?>
            <p><?php display_flash_message_by_name(ERROR_HANDLER); ?></p>
        <?php endif; ?>
    </div>
    <script>
        showFlashMessage('error');
    </script>
<?php elseif (isset_flash_message_by_type(FLASH_SUCCESS)) : ?>
    <!-- MODAL DE SUCCÈS -->
    <div class="modal success gap-1 flex flex-al" id="flash">
        <i class="fa fa-circle-check"></i>
        <p><?php display_flash_message_by_type(FLASH_SUCCESS); ?></p>
    </div>
    <script>
        showFlashMessage('success');
    </script>
<?php endif; ?>