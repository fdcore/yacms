<?php
/**
 * Created by PhpStorm.
 * User: nyashkin
 * Date: 24.05.15
 * Time: 1:32
 */

use Respect\Validation\Validator as v;

class Valid {

    /**
     * @param array $rules
     * @param array $data
     * @return array|bool
     */
    function is_valid($rules=array(), $data=array()){

        if(!is_array($rules) || count($rules) == 0) return false;
        if(!is_array($data)  || count($data) == 0) return false;

        $v = new v;

        $is_valid = true;

        // перебираем массив правил
        foreach($rules as $key => $rule){

            // нет ключа в массиве данных
            if(!isset($data[$key])) return array('valid' => false, 'key' => $key, 'rule'=> 'required', 'message' => "error_{$key}_required");

            // проверяем на массив правил или одиночное
            if(is_string($rule)){

                // одиночное правило
                $is_valid = $this->_valid_block($rule, false, $data[$key]);
                if(!$is_valid) return array('valid' => $is_valid, 'key' => $key, 'rule'=> $rule, 'value' => $data[$key], 'message' => "error_{$key}_{$rule}");

            } else{

                // проверяем на массив правили или это одно правило с параметром
                if(count($rule) > 1) {

                    // это массив правил, перебираем их
                    foreach ($rule as $k => $r) {

                        $is_valid = $this->_valid_block($k, $r, $data[$key]);

                        if (!$is_valid) return array('valid' => $is_valid, 'key' => $key, 'rule' => $k, 'message' => "error_{$key}_{$k}");
                    }

                } else{
                    // это одно правило с параметрами
                    $is_valid = $this->_valid_block(key($rule), $rule, $data[$key]);
                    if (!$is_valid) return array('valid' => $is_valid, 'key' => $key, 'rule' => key($rule), 'message' => "error_{$key}_".key($rule));

                }

            }

        }

        return array('valid' => $is_valid);
    }


    /**
     * валидируем отдельный блок правил
     *
     * @param $rule
     * @param $params
     * @param $value
     * @return mixed
     */
    private function _valid_block($rule, $params, $value){

        // если есть параметры, вызываем через жопу
        if(is_array($params)) {

            $v = new v;

            $check = call_user_func_array(array($v, $rule), $params);
            $is_valid = $check->validate($value);

        } else $is_valid = v::$rule()->validate($value);

        return $is_valid;
    }

}