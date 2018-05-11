<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
    'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => $backUrl),
    'last_menu' => (Object)array('name' => "编辑", 'url' => "#"),
);

$this->renderPartial('_form', array(
    'model' => $model,
    'operation' => $operation,
    'has_operation' => $has_operation,
    'backUrl' => $backUrl,
    'result' => $result,
));
