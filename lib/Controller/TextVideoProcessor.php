<?php
/**
 * Created by Vadym Radvansky
 * Date: 2/4/14 9:49 AM
 */
namespace rvadym\videojs;
class Controller_TextVideoProcessor extends \AbstractController {
    function init() {
        parent::init();
    }
    function prepareText($text) {
        $hashes = $this->findVideoHashes($text);
        $videos = $this->findVideoInDB($hashes);
        $text = $this->renderEmbedVideo($text,$videos);
        return $text;
    }
    function findVideoHashes($text) {
        $matches = array();
        preg_match_all('/\[(video-[a-zA-Z0-9]*)\]/', $text, $matches);
        if (isset($matches[1])) {
            return $matches[1];
        } else {
            return array();
        }
    }
    function cleanHash($hash) {
        $new_hash = strip_tags($hash);
        return $new_hash;
    }
    function findVideoInDB($hashes) {
        $videos = array();
        $counter = 0;
        foreach ($hashes as $hash) {
            $model = $this->getVideoIfExist($hash);
            $videos[$counter] = array(
                'hash'        => $hash,
                'video_model' => $model
            );
            $counter++;
        }
        return $videos;
    }
    function getVideoIfExist($hash) {
        $m = $this->add('rvadym\\videojs\\Model_Video')/*->debug()*/;
        $m->tryLoadBy('hash',$this->cleanHash($hash));
        if ($m->loaded()) {
            return $m;
        } else {
            return false;
        }
    }
    // https://$bucket.s3-external-3.amazonaws.com/
    function renderEmbedVideo($text,$video_arr) {
        $player = $this->add('rvadym\\videojs\\View_Player');
        foreach ($video_arr as $video) {
            if ($video['video_model']) {
                $player->model = $video['video_model'];
//                $player->bucket = $video['video_model']->get('bucket');
//                $player->poster = $video['video_model']->get('poster');
//                $player->mp4 = $video['video_model']->get('mp4');
//                $player->webm = $video['video_model']->get('webm');
//                $player->captions = $video['video_model']->get('captions');

                $text = str_replace(
                    '['.$video['hash'].']',
                    $player->getEmbedHTML(),
                    $text
                );
            }
        }
        return $text;
    }

}