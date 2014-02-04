<?php
/**
 * Created by Vadym Radvansky
 * Date: 1/31/14
 * Time: 9:27 AM
 */
namespace rvadym\videojs;
class View_Player extends \AbstractView {
    public $model;
    function init() {
        parent::init();
        $this->addStaticFiles();
    }
    function addStaticFiles() {
        $this->api->jquery->addStaticInclude('js/video');
        $this->api->jquery->addStaticStylesheet('css/video-js');
    }
    function getEmbedHTML() {
        //$this->template->set('bucket',$this->model->get('bucket')); // https://$bucket.s3-external-3.amazonaws.com/
        $this->template->set('poster',
            'https://'.$this->model->get('bucket').'.s3-external-3.amazonaws.com/'.$this->model->get('poster')
        );
        $this->template->set('mp4',
            'https://'.$this->model->get('bucket').'.s3-external-3.amazonaws.com/'.$this->model->get('mp4')
        );
        $this->template->set('webm',
            'https://'.$this->model->get('bucket').'.s3-external-3.amazonaws.com/'.$this->model->get('webm')
        );
        $this->template->set('captions',
            'https://'.$this->model->get('bucket').'.s3-external-3.amazonaws.com/'.$this->model->get('captions')
        );

        return $this->template->render();
    }
    function defaultTemplate() {
		// add add-on locations to pathfinder
		$l = $this->api->locate('addons',__NAMESPACE__,'location');
		$addon_location = $this->api->locate('addons',__NAMESPACE__);
		$this->api->pathfinder->addLocation($addon_location,array(
			//'js'=>'templates/js',
			//'css'=>'templates/css',
            'template'=>'templates',
		))->setParent($l);
        return array('view/player');
    }


    private function getSymLinkPublic() {
        return str_replace(array('\\','/'),'_',__NAMESPACE__);
    }
}