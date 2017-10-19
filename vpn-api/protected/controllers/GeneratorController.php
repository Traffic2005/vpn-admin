<?php

use App\Managers\GeneratorManager;
use App\controllers\BaseController;


/**
 * Class TransferController
 */
class GeneratorController extends BaseController
{
    /**
     * @var GeneratorManager
     */
    private $generatorManager;

    /**
     *
     */
    public function init(){
        $this->generatorManager = new GeneratorManager();
    }

    /**
     *
     */
    public function actionFillUsers()
	{
        $this->generatorManager->fillUser();
	}


    public function actionFillTransfers()
	{
        $this->generatorManager->fillTransfers();
	}




}