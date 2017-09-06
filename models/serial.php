<?php

use Phalcon\Mvc\Model;

class Serial extends Model{
    public $id;
    public $label;
    public $tmdb_id;
    public $title;
    public $title_en;
    public $image;
    public $image_back;
    public $rating;
    public $stars;
    public $year;
    public $description;

    public function initialize()
    {
        $this->hasMany('id','CategorySerial','serial_id');
        $this->hasMany('id','Series','serial_id');
    }

    public function setSlug()
    {
        $text = $this->title_en;
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
//        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
            return 'serial'.$this->id;
        }

        $this->label = $text;
    }

    public function getRating(){
        switch($this->rating){
            case 'TV-G' :
            case 'TV-Y' : return '+0'; break;
            case 'TV-Y7' : return '+7'; break;
            case 'TV-PG' :
            case 'TV-14' : return '+14'; break;
            case 'TV-MA' : return '+17'; break;
            case '16' : return '+16'; break;
            case '18' : return '+18'; break;
            default : return '+14'; break;
        }
    }

    public function beforeDelete()
    {
        foreach(CategorySerial::find('serial_id = '.$this->id) as $cf){
            $cf->delete();
        }
    }

    public function beforeSave()
    {
        if(!$this->label && $this->label == ''){
            $this->setSlug();
        }
    }

    public function getImageThumb(){
        return 'i/s/'.$this->label.'/1';
    }

    public function getImageBackThumb(){
        return 'i/s/'.$this->label.'/2';
    }

    public function getForApi($type="full"){
        $res = $this->toArray([
            'id',
            'label',
            'tmdb_id',
            'title',
            'title_en',
            'stars',
            'year',
            'description'
        ]);

        $res['series'] = [];

        foreach ($this->Series as $item){
            if(!isset($res['series'][$item->season])){
                $res['series'][$item->season] = [];
            }

            if(!isset($res['series'][$item->season][$item->series_num])){
                $res['series'][$item->season][$item->series_num] = $item->getForApi();
            }
        }

        $res['category'] = [];

        foreach ($this->getCategorySerial() as $item){
            $res['category'][] = $item->getCategory()->getForApi("tiny");
        }

        $res["image"] = $this->getDI()['url']->get($this->getImageThumb());
        $res["imageBack"] = $this->getDI()['url']->get($this->getImageBackThumb());
        $res["rating"] = $this->getRating();

        $res["url"] = $this->getDI()['publicUrl']->get('serial/'.$this->label);

        return $res;
    }
}