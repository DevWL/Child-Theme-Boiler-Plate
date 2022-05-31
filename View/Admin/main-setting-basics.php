<?php
namespace My\Lib;

use My\Lib\Helpers\WpDebug\debugEnv;

?>

<div id="<?= $pageslug ?>" class="custom-plugin-page <?= $pageslug ?>" style="background: #ccf; padding: 40px 60px; width 100%; margin-left: -20px;">

    <h1>Basics (main-setting-basics.php)</h1>

    <?= bloginfo('name') ?> (main-setting-basics.php)

    <?php
        /** @var \My\Lib\Helpers\WpForms\Form $form */
        echo $form->renderForm();
    ?>

</div>

<?php
    // new DebugEnv();
    // debugEnv::getDeclaredConstants();
    // echo '<pre>' . print_r(get_defined_vars(), true) . '</pre>'
?>