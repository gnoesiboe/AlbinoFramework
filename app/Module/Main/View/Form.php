<?php

/**
 * @var \App\Form\PageForm $form
 */

?>
<?php if ($form->hasErrors() === true): ?>
<p style="color: red;">Een of meerdere velden zijn niet (correct) ingevuld!</p>
<?php endif; ?>
<form action="#" method="POST">
  <fieldset>
    <legend>test</legend>
    <div>
      <label for="title">title</label><br />
      <input type="text" id="title" name="title" value="<?php echo $form->getField('title')->getValue(); ?>" /><br />
      <?php echo $this->renderAction('Main', 'Form', 'errors', array('field' => $form->getField('title'))); ?>

      <label for="description">description</label><br />
      <textarea rows="4" cols="40" id="description" name="description"><?php echo $form->getField('description')->getValue(); ?></textarea><br />
      <?php echo $this->renderAction('Main', 'Form', 'errors', array('field' => $form->getField('description'))); ?>

      <label for="amount">Amount</label><br />
      <input type="text" id="amount" name="amount" value="<?php echo $form->getField('amount')->getValue(); ?>" /><br />
      <?php echo $this->renderAction('Main', 'Form', 'errors', array('field' => $form->getField('amount'))); ?><br />

      <input type="submit" />
    </div>
  </fieldset>
</form>