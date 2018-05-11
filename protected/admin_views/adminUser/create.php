<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
    'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => $backUrl),
    'last_menu' => (Object)array('name' => "新增", 'url' => "#"),
);

$this->renderPartial('_form', array(
    'model' => $model,
    'role_array' => $role_array,
    'has_auths' => $has_auths,
    'operation' => $operation,
    'backUrl' => $backUrl,
    'result' => $result,
));
