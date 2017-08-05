<form method="<?php echo $config['struct']['method']; ?>"
      action="<?php echo $config['struct']['action']; ?>"
      class="<?php echo $config['struct']['class']; ?>">


    <?php foreach ($config['data'] as $name => $attributs): ?>

        <?php if ($attributs['type'] == 'email'
                || $attributs['type'] == 'text'
                || $attributs['type'] == 'password'): ?>
            <input type="<?php echo $attributs['type']; ?>"
                   name="<?php echo $name; ?>"
                   placeholder="<?php echo $attributs['placeholder']; ?>"
                   <?php echo (isset($attributs['required']) ? 'required="required"' : ''); ?>
                    >
            <br>
        <?php endif; ?>

    <?php endforeach; ?>

    <input type="submit" value="<?php echo $config['struct']['submit']; ?>">
</form>