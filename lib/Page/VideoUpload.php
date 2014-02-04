<?php
/**
 * Created by Vadym Radvansky
 * Date: 1/31/14 3:31 PM
 */
namespace rvadym\videojs;
class Page_VideoUpload extends \Page {
    function page_index() {
        $cr = $this->add('rvadym\\videojs\\CRUD_Video');
        $cr->setModel('rvadym\\videojs\\Video',array(
            'title','hash','created_at','updated_at',
        ));
    }
    function page_add() {
        //$s3 = $this->add('atk4\\x_s3\\Controller_S3');
        //$s3->getS3()->putBucket('html5_video');
        //var_dump($s3->getS3()->listBuckets());

        $this->add('Form_HTML5Upload');
    }
    function page_edit() {

    }
}