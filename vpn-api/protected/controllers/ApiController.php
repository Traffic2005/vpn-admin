<?php
/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 16.10.17
 * Time: 10:49
 */

//use App\controllers\BaseController;
use App\Managers\ResponseManager;
use App\Managers\ApiManager;

class ApiController extends Controller
{
    // Members
    /**
     * Key which has to be in HTTP USERNAME and PASSWORD headers
     */
    Const APPLICATION_ID = 'ASCCPE';

    /**
     * Default response format
     * either 'json' or 'xml'
     */
    private $format = 'json';


    /** @var  ApiManager $apiManager */
    private $apiManager;

    /**
     * @var ResponseManager
     */
    private $responseManager;

    public function init()
    {
        $this->apiManager = ApiManager::create();
        $this->responseManager = ResponseManager::create();
    }

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array();
    }


    // Actions
    public function actionList()
    {
        $modelName = Yii::app()->request->getQuery('model');
        $this->sendMessage($this->apiManager->listData($modelName));
    }

    public function actionView()
    {
        // Check if id was submitted via GET
        if ( ! isset($_GET['id'])) {
            $this->responseManager->sendResponse(500, 'Error: Parameter <b>id</b> is missing');
        }
        $this->sendMessage(
            $this->apiManager->view(
                Yii::app()->request->getQuery('model'),
                Yii::app()->request->getQuery('id')
            )
        );
    }

    public function actionCreate()
    {
        $this->sendMessage(
            $this->apiManager->createRecord(
                Yii::app()->request->getQuery('model'),
                Yii::app()->request->getRestParams()
            )
        );
    }

    public function actionUpdate()
    {
        $this->sendMessage(
            $this->apiManager->update(
                Yii::app()->request->getQuery('model'),
                Yii::app()->request->getQuery('id'),
                Yii::app()->request->getRestParams()
            )
        );
    }

    public function actionDelete()
    {
        $this->sendMessage(
            $this->apiManager->delete(
                Yii::app()->request->getQuery('model'),
                Yii::app()->request->getQuery('id')
            )
        );
    }


    /**
     * @param $result
     */
    private function sendMessage($result)
    {
        $this->responseManager->sendResponse($result['statusCode'], $result['data']);
        Yii::app()->end();
    }
}