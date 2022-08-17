<div class="bg-white p-2 rounded-1" style="max-width: 650px">
    <h3 class="text-primary fw-bold">Error</h3>

    <div class="bg-danger text-white p-2 rounded-1 ">
        <div class="fw-bold"><?php echo $message; ?>>
        </div>

        <?php if (isset($sql)) : ?>
            <div class="py-2">SQL : <?php echo $sql; ?></div>
        <?php endif; ?>
    </div>
</div>