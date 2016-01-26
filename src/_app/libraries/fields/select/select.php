<?php

class Select_field extends Field{

    function display($data){

        if(isset($data['options_type']) && $data['options_type'] == 'use_value_as_key'){
            $options = array();

            foreach($data['options'] as $k => $f)
                $options[$f] = $f;

            $data['options'] = $options;

        }

        unset($data['options_type']);
      
        return $data;
    }

    function save($field_data, $value){
        return $value;
    }
}