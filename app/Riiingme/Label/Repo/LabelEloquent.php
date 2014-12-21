<?php namespace Riiingme\Label\Repo;

use Riiingme\Label\Repo\LabelInterface;
use Riiingme\Label\Entities\Label as M;

class LabelEloquent implements LabelInterface {

    public function __construct(M $label){

        $this->label = $label;
    }
    public function getAll(){

        return $this->label->all();
    }
    public function find($id){

        return $this->label->with(array('metas'))->findOrFail($id);
    }

    public function create(array $data){

        $label = $this->label->create([
            'meta'      => $data['id'],
            'user_id'   => $data['user_id'],
            'type_id'   => $data['type_id'],
            'groupe_id' => $data['groupe_id'],
            'rang'      => $data['rang']
        ]);

        if(!$label){
            return false;
        }

        return $label;
    }

}
