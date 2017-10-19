<?php
/* @var $this UserController */

$jsFile = Yii::app()->baseUrl . "/assets/dist/bundle.js";
$cs     = Yii::app()->clientScript;

$cs->registerScriptFile($jsFile, CClientScript::POS_END);
$cs->registerScriptFile('https://code.jquery.com/jquery-3.2.1.min.js', CClientScript::POS_END);
$this->pageTitle = Yii::app()->name;
?>



