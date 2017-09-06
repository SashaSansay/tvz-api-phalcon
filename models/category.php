<?php

use Phalcon\Mvc\Model;

class Category extends Model{
    public $id;
    public $tmdb_id;
    public $title;

    public function initialize()
    {
        $this->hasMany('id','CategorySerial','category_id');
        $this->hasMany('id','CategoryFilm','film_id');
    }

    public function getForApi($type="full"){
        $category = $this->toArray();
        if($type == 'full'){
            $category['serial'] = [];
            $category['film'] = [];

            foreach ($this->getCategorySerial() as $item){
                $category['serial'][] = $item->getSerial()->getForApi();
            }
            foreach ($this->getCategoryFilm() as $item){
                $category['film'][] = $item->getFilm()->getForApi();
            }
        }elseif ($type=="tiny"){

        }

        return $category;
    }
}