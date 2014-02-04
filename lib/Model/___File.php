<?php
/**
 * Created by Vadym Radvansky
 * Date: 1/31/14 3:58 PM
 */
namespace rvadym\videojs;
class Model_File extends \Model_Table {
    public $table = 'rvadym_videojs_file';
    public $types = array(
        'poster',
        'mp4',
        'webm',
        'captions',
    );
    function init() {
        parent::init();
        $this->addField('name','title');
        $this->addField('title');
        $this->addField('type')->listData($this->types);
        $this->addField('filename');
        $this->hasOne('rvadym\\videojs\\Video','video_id');

        $this->addField('created_at')->type('datetime');
        $this->addField('updated_at')->type('datetime');

        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
            $q->set('updated_at',$q->expr('now()'));
        });
    }
}