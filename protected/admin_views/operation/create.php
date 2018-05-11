<?php
$this->breadcrumbs = array(
    'menu' => (Object)array('name' => $this->crumbs['parent'], 'url' => "#"),
    'sub_menu' => (Object)array('name' => $this->crumbs['child'], 'url' => $backUrl),
    'last_menu' => (Object)array('name' => "新增", 'url' => "#"),
);

$this->renderPartial('_form', array(
    'model' => $model,
    'menu_model' => $menu_model,
    'parent_menu' => $parent_menu,
    'backUrl' => $backUrl,
    'result' => $result,
));
