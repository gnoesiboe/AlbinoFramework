<?php

/**
 * @var \Albino\Collection\Model $pages
 */

?>
<div id="page-pages-index">
  <ul>
    <?php foreach ($pages as $page): /* @var \App\Model\Page $page */?>
    <li><a href="<?php echo $page->getUri(); ?>"><?php echo $page->getTitle(); ?></a></li>
    <?php endforeach; ?>
  </ul>
</div>