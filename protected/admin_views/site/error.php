<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
);
?>

<div class="error_code">
	<?php echo $code; ?>
</div>

<div class="error_msg">
	<?php echo CHtml::encode($message); ?>
</div>