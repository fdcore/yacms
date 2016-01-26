<?php

class Fields {

    private $data = array(); // данные полей
    private $name_wrap = 'form'; // form[name] например
    private $inner_array = false; // если вложенный множественный массив
    private $row_id = ''; // id вложенного массива
    private $parsed = array(); // parsed JSON
    private $_temp_data = array();
    private $rules = array(); // List rules
    private $required = array();
    private $_temp = array();

    function __construct( $config=array() ){}

    function parse(array $array, $data = null){

        if($data != null) $this->data = $data;

        $this->_temp_data = array();

        // поля разделены на табы
        if(isset($array['tabs'])){

            // обрабатываем каждый таб отдельно
            foreach($array['tabs'] as $tab_name => $tab){
                if(isset($tab['fields']))
                    $array['tabs'][$tab_name]['fields'] = $this->parse_fields($array['tabs'][$tab_name]['fields'], $this->data);
            }
        } elseif(isset($array['fields'])){
            $array['fields'] = $this->parse_fields($array['fields'], $this->data);

        } else{
            $array = $this->parse_fields($array, $this->data);
        }

        $this->parsed = $array;

        return $array;

    }

    function get_rules()
    {
        return $this->rules;
    }

    function get_required(){
        return $this->required;
    }

    function _parse_rules($name, $field){

        if(isset($field['required'])) $this->required[] = $name;

        if (!isset($field['rules'])) return;

        $id = str_replace('[', '_', $name);
        $id = str_replace(']', '', $id);



        $this->rules[$id] = array(
            'identifier' => $id,
            'rules' => $field['rules']
        );
    }

    function parse_fields($fields, $values=array()){

        $data = array();

        foreach($fields as $name => $field){

          if(is_numeric($name)) {
                $data['rows'][] = $this->parse_fields($field, $values);
                continue;
            }

          $field = $this->create_default_field($name, $field);
          $field = $this->use_value_for_field($field, $values);

          // ищем тип поля и подгружаем его если нет класса, классы имеют префикс _field
          $path = dirname(__FILE__) . "/fields/" . strtolower($field['type']) . '.php'; // путь к типу поля

          $class_field = ucfirst($field['type']) . "_field"; // класс поля

          // подгружаем тока если класса нет
          if(file_exists($path) && !class_exists($class_field)){
              include_once $path;
          }

          if(class_exists($class_field)){
              // конструктор получает данные всех значений полей
              $field_file_class = new $class_field($values);

              if(method_exists($field_file_class, 'display'))
                  $field = $field_file_class->display($field);
          }


          if( in_array($field['type'], array('grid', 'grid_read_only', 'matrix')) ){
                $this->inner_array = true;
                $this->matrix_name = $name;

                if(!isset($field['value'])) $field['value'] = array();

                $field['fields'] = $this->parse_fields($field['fields'], array());
                // $field['values'] = $this->parse_fields($field['fields'], $field['value']);

                // приоритет на значения поля после обработки
                if(isset($field['value'])) {
                    $values[$name] = $field['value'];

                }
//
                if(isset($values[$name]) && is_array($values[$name]) && count($values[$name]) > 0){

                    foreach($values[$name] as $_data){
                        $field['values'][] = $this->parse_fields($field['fields'], $_data);
                    }
                }


                // print_var($field);



//                $value_grid = $this->type_grid($field, $values);

//                if($value_grid && count($value_grid) > 0){
//                    foreach($value_grid as $data){
//                        $this->row_id = 'row_'.$data[$field['data']['row_id']];
//                        $field['values'][] = $this->parse_fields($field['fields'], $data);
//                    }
//                }

                $this->inner_array = false;
            }


             if($this->inner_array) $name_wrap = $this->matrix_name; else $name_wrap = $this->name_wrap;

            if($name_wrap != ''){
                if($this->inner_array) $field['prefix'] = $name_wrap;
                if($this->inner_array) $field['form_name'] = $name_wrap."[$name][$this->row_id]";
                else $field['form_name'] = $name_wrap."[$name]";
            } else $field['form_name'] = $name;

          $this->row_id = '';

          $this->create_rules_list($field);

          $this->fields[] = $field;

          $data[$name] = $field;

        } // foreach

        // print_var($data);

        return $data;

    }

   // создаём список правил
    function create_rules_list($field){
          // обязательное поле
          if(isset($field['required']) && $field['required']){
            $this->required[$field['id']]=array(
              'display' => $field['display'],
              'name' => $field['name'],
            );
          }

    }

    // присваивание значения полю
    function use_value_for_field($field, $values){

      if(isset($field['name']) && isset($values[$field['name']]))
        $field['value'] = $field['_value'] = $values[$field['name']];


      // стандартное значение если нет значения
      if(!isset($field['value']) && isset($field['default'])) $field['value'] = $field['default'];

      return $field;
    }

    // обработка стандартных значений поля
    function create_default_field($name, $field){

        if($field == null && is_string($name)) $field = $name;

//        // если у поля совсем нет параметров
        if(!is_array($field)){
            $_tmp = $field;
            $field = array('type' => 'text');
            if(is_string($name) && is_string($field)) $field['display'] = $field; else $field['display'] = ucfirst($_tmp);
            if(is_string($name)) $field['name'] = strtolower($name);
        }

        if($name == 'name') var_dump($field);

        // если имя поля не указано, а оно очень нужное
        if(!isset($field['display'])) $field['display'] = ucfirst($name);
        if(!isset($field['name'])) $field['name'] = strtolower($name);

        if($field['display'] == '') $field['display'] = $field['name'];
//
//        // если нет типа поля, будет текстовое
        if(!isset($field['type']) || $field['type'] == '') $field['type'] = 'text';

        $field['id'] = uniqid($field['name'].'_');
        return $field;
    }
}


class Field{

  function __construct($values){}

  function display($data){}

  function save($field_data, $value){}

}
