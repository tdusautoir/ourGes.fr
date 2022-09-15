<?php if (isset_flash_message_by_type(FLASH_ERROR)) : ?>
    <div class="modal error" id="flash">
        <i class="fa fa-cirlce-xmark"></i>
        <p><?php display_flash_message_by_type(FLASH_ERROR); ?></p>
    </div>
    <script>
        showFlashMessage('error');
    </script>
<?php elseif (isset_flash_message_by_type(FLASH_SUCCESS)) : ?>
    <!-- MODAL DE SUCCÈS -->
    <div class="modal success" id="flash">
        <i class="fa fa-circle-check"></i>
        <p><?php display_flash_message_by_type(FLASH_SUCCESS); ?></p>
    </div>
    <script>
        showFlashMessage('success');
    </script>
<?php endif; ?>