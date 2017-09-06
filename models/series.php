<?php

use Phalcon\Mvc\Model;

class Series extends Model{
    public $id;
    public $views_count;
    public $publish;
    public $title;
    public $video_src;
    public $commercial_id;
    public $serial_id;
    public $season;
    public $series_num;

    public function initialize(){
        $this->belongsTo('serial_id','Serial','id');
        $this->belongsTo('commercial_id','Commercial','id');
    }

    public function getImageThumb(){
        return '/i/p/'.$this->id;
    }

    public function getForApi($type="full"){
        $res = $this->toArray([
            'id',
            'views_count',
            'title',
            'video_src',
            'commercial_id',
            'season',
            'series_num'
        ]);

        $res['image'] = $this->getDI()['url']->get($this->getImageThumb());
        $res["url"] = $this->getDI()['publicUrl']->get('serial/'.$this->getSerial()->label.'/'.$this->season.'/'.$this->series_num);

        if($type=="full"){
            $res["commercial"] = [];

            $commercial = $this->getCommercial();

            if($commercial !== false){
                $res["commercial"] = $commercial->toArray();
            }
        }

        return $res;
    }

}