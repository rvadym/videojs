<?php
/**
 * Created by Vadym Radvansky
 * Date: 1/31/14 3:58 PM
 */
namespace rvadym\videojs;
class Model_Video extends \Model_Table {
    public $table = 'rvadym_videojs_video';
    function init() {
        parent::init();
        $this->addField('title')->mandatory('required');
        $this->addField('poster')->mandatory('required');
        $this->addField('mp4')->mandatory('required');
        $this->addField('webm')->mandatory('required');
        $this->addField('captions');
        $this->addField('bucket')->mandatory('required');

        /*
         * do we need relations here?
        $this->hasOne('rvadym\\videojs\\File','video_mp4');
        $this->hasOne('rvadym\\videojs\\File','video_webm');
        $this->hasOne('rvadym\\videojs\\File','captions');
        */

        $this->addField('hash')->mandatory('required');
        $this->addField('created_at')->type('datetime');
        $this->addField('updated_at')->type('datetime');

        $this->addHook('beforeInsert',function($m,$q){
            $q->set('created_at',$q->expr('now()'));
            $q->set('updated_at',$q->expr('now()'));
            $q->set('hash',$m->generateUniqueHash());
        });
        $this->addHook('beforeModify',function($m,$q){
            $q->set('updated_at',$q->expr('now()'));
        });
        $this->addHook('beforeDelete',function($m,$q){
            $m2 = $m->add(get_class($m))->tryLoad($m->id);
            if ($m2->loaded()) {
                $s3 = $m->add('atk4\\x_s3\\Controller_S3');
                if ($m2['poster'])   $s3->deleteFile($m2['bucket'],$m2['poster']);
                if ($m2['mp4'])      $s3->deleteFile($m2['bucket'],$m2['mp4']);
                if ($m2['webm'])     $s3->deleteFile($m2['bucket'],$m2['webm']);
                if ($m2['captions']) $s3->deleteFile($m2['bucket'],$m2['captions']);
            }
        });
    }
    function generateUniqueHash() {
        $count = 0;
        $hash = uniqid('video-');
        $unique_hash = $hash;
        while ($this->checkIfHashExist($unique_hash)) {
            $count++;
            $unique_hash = $hash.$count;
            if ($count >= 100) {
                throw $this->exception('Something went wrong with creation of unique hash');
            }
        }
        return $unique_hash;
    }
    function checkIfHashExist($value) {
        $m_chek = $this->api->add(get_class($this));
        $m_chek->tryLoadBy('hash',$value);

        if ($m_chek->loaded()){
            return true;
        }else{
            return false;
        }
    }
}