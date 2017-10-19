<?php namespace App\controllers;

abstract class  BaseController extends \Controller
{
    /**
     * @param $data
     */
    protected function outputJson($data)
    {
            $this->layout = false;
            header('Content-type: application/json');
            echo \CJSON::encode($data);
            \Yii::app()->end();
    }


}