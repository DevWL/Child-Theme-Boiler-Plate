<?php

use My\Lib\Helpers\WpForms\Form;

?>

<h1>Settings page</h1>
<?= bloginfo('name') ?>

<h2>Form</h2>


<?php 
    $form = new Form($page);
    var_dump($form->renderForm()); 
?>


<h2>End form</h2>