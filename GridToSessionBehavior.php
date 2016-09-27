<?php

/**
 * @copyright Copyright (c) 2016 Ilya Shumilov
 * @version 1.0.0
 * @link https://github.com/restlin/GridToSessionBehavior
 */
namespace app\components;

use Yii;

/**
 * This behavior has functions for save and loads filters,sort and pagination keys from GridView.
 * Data save in session. All functions must be calls manualy from owner (controller) code.
 *
 * @author Ilya Shumilov <restlinru@yandex.ru>
 */
class GridToSessionBehavior extends \yii\base\Behavior
{
    /**
     * Массив базовых параметров
     * @var array
     */
    public $baseParams = [
        'sort' => '',
        'page' => 1,
        'per-page' =>20,
    ];
    /**
     * Generate unique key for storage data in session
     * @return string
     */
    public function keyFromRoute()
    {
        $login = Yii::$app->user->login;
        return $login.'/'.$this->owner->id.'/'.$this->owner->action->id;
    }
    /**
     * Save data to session
     * @param string $className class name for finding filters
     * @param array|null $adds additional fields [key=>value(default)]
     */
    public function saveParams($className,$adds)
    {
        $request = Yii::$app->request;
        $list = [
            'sort' => $request->get('sort',''),
            'page' => $request->get('page',1),
            'per-page' => $request->get('page',20),
            $className => $request->get($className,[]),
        ];
        $params = is_array($adds) && $adds ?  array_merge($this->baseParams,$adds) : $this->baseParams;
        foreach($params as $key => $default) {
            $list[$key] = $request->get($key,$default);
        }
        Yii::$app->session->set($this->owner->keyFromRoute(),$list);
    }
    /**
     * Load data to global array $_GET
     * @return boolean
     */
    public function loadParams()
    {
        $list = Yii::$app->session->get($this->owner->keyFromRoute());
        $request = Yii::$app->request;
        if(!$list) {
            return false;
        }
        foreach($list as $attr => $value) {
            $_GET[$attr] = $request->get($attr,$value);
        }
        return true;
    }
}
