<?php
/**
 * Created by Vadym Radvansky
 * Date: 2/2/14 6:22 AM
 */
namespace rvadym\videojs;
class Grid_Video extends \Grid {
    function init() {
        parent::init();
    }
    function setModel($model, $actual_fields = UNDEFINED) {
        $m = parent::setModel($model, $actual_fields);
        $this->addAddButton();
        $this->addEditAndDelete();
        return $m;
    }
    private function addEditAndDelete() {
        $this->addColumn('button','edit');
        $this->addColumn('button','delete');
    }
    private function addAddButton() {
        $b = $this->addButton('Add');
        $b->js('click')->univ()->redirect($this->api->url('./add'));
    }
    function formatRow() {
        parent::formatRow();
    }
}