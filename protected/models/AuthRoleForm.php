<?php
/**
*
*/
class AuthRoleForm extends CFormModel
{
    public $name;

    public $description;

    public $priority;

    public $is_parent;

    public $parent;

    public $icon;

    public $url;

    public function rules()
    {
        return array(
            array('name, description, priority, is_parent, parent, icon, url', 'required'),
        );
    }
}
