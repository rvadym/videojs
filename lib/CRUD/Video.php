<?php
/**
 * Created by Vadym Radvansky
 * Date: 2/4/14 11:17 AM
 */
namespace rvadym\videojs;
class CRUD_Video extends \CRUD {
    function init() {
        $this->allow_add = false;
        $this->allow_edit = false;
        parent::init();
        $this->addAddButton();
    }
    private function addAddButton() {
        $b = $this->grid->addButton('Add');
        $b->js('click')->univ()->redirect($this->api->url('./add'));
    }
}