<?php

/**
 * @var \Albino\FOrmField $field
 */

?>
<ul class="errors">
  <?php foreach ($field->getErrors() as $message): ?>
  <li><?php echo $message; ?></li>
  <?php endforeach; ?>
</ul>