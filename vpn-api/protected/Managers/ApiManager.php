<?php namespace App\Managers;

/**
 * Created by PhpStorm.
 * User: traffic2005
 * Date: 13.10.17
 * Time: 12:54
 */


class ApiManager extends BaseManager
{
    /**
     * @param $modelName
     *
     * @return array
     */
    public function listData($modelName)
    {
        // Get the respective model instance
        switch ($modelName) {
            case 'user':
                //$models = \User::model()->findAll();
                $models = \User::model()->getAllUsersWithCompanyName();
                break;
            case 'company':
                $models = \Company::model()->findAll();
                break;
            default:
                // Model not implemented error
                return [
                    'statusCode' => 501,
                    'data'       =>
                        sprintf(
                            'Error: Mode <b>list</b> is not implemented for model <b>%s</b>',
                            $modelName
                        )
                ];
        }

        // Did we get some results?
        if (empty($models)) {
            // No
            $statusCode = 200;
            $resultData = sprintf('No items where found for model <b>%s</b>', $modelName);
        } else {
            // Prepare response
            if ($models[0] instanceof \CActiveRecord) {
                $rows = array();
                foreach ($models as $model) {
                    $rows[] = $model->attributes;
                }
            } else {
                $rows = $models;
            }

            $statusCode = 200;
            $resultData = \CJSON::encode($rows);
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];
    }

    /**
     * @param $modelName
     * @param $id
     *
     * @return array
     */
    public function view($modelName, $id)
    {

        switch ($modelName) {
            // Find respective result
            case 'user':
                $result = \User::model()->findByPk($id);
                break;
            case 'company':
                $result = \Company::model()->findByPk($id);
                break;
            case 'report':
                $reportManager = ReportManager::create();
                $result        = $reportManager->getReports($id);
                break;
            default:
                return [
                    'statusCode' => 501,
                    'data'       => sprintf(
                        'Mode <b>view</b> is not implemented for result <b>%s</b>',
                        $modelName
                    )
                ];
        }
        // Did we find the requested result? If not, raise an error
        if (is_null($result)) {
            $statusCode = 404;
            $resultData = 'No Item found with id ' . $id;
        } else {
            $statusCode = 200;
            $resultData = \CJSON::encode($result);
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];
    }

    public function createRecord($modelName, $postData)
    {
        switch ($modelName) {
            // Get an instance of the respective model
            case 'user':
                $model = new \User;
                break;
            case 'company':
                $model = new \Company();
                break;
            case 'generate':
                return $this->generate();
                break;
            default:
                return [
                    'statusCode' => 501,
                    'data'       =>
                        sprintf('Mode <b>create</b> is not implemented for model <b>%s</b>',
                            $modelName
                        )
                ];
        }
        // Try to assign POST values to attributes
        foreach ($postData as $var => $value) {
            // Does the model have this attribute? If not raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                return [
                    'statusCode' => 500,
                    'data'       => sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>', $var,
                        $modelName
                    )
                ];
            }
        }
        // Try to save the model
        if ($model->save()) {
            $statusCode = 200;
            $resultData = \CJSON::encode($model);
        } else {
            $statusCode = 500;
            $resultData = $this->prepareErrorMsg($modelName, $model, 'create');;
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];

    }

    public function update($modelName, $id, $putParams)
    {

        switch ($modelName) {
            // Find respective model
            case 'user':
                $model = \User::model()->findByPk($id);
                break;
            case 'company':
                $model = \Company::model()->findByPk($id);
                break;
            default:
                return [
                    'statusCode' => 501,
                    'data'       =>
                        sprintf('Error: Mode <b>update</b> is not implemented for model <b>%s</b>',
                            $modelName
                        )
                ];
        }
        // Did we find the requested model? If not, raise an error
        if ($model === null) {
            return [
                'statusCode' => 400,
                'data'       => sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $modelName, $id
                )
            ];
        }

        // Try to assign PUT parameters to attributes
        foreach ($putParams as $var => $value) {
            // Does model have this attribute? If not, raise an error
            if ($model->hasAttribute($var)) {
                $model->$var = $value;
            } else {
                return [
                    'statusCode' => 500,
                    'data'       =>
                        sprintf('Parameter <b>%s</b> is not allowed for model <b>%s</b>',
                            $var, $modelName
                        )
                ];
            }
        }
        // Try to save the model
        if ($model->save()) {
            $statusCode = 200;
            $resultData = \CJSON::encode($model);
        } else {
            $resultData = $this->prepareErrorMsg($modelName, $model, 'create');
            $statusCode = 500;
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];
    }

    private function generate()
    {
        $generateManager = GeneratorManager::create();
        $result          = $generateManager->fillTransfers();
        if ($result) {
            $statusCode = 200;
            $resultData = '';
        } else {
            $statusCode = 500;
            $resultData = 'System error';
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];
    }

    /**
     * @param $modelName
     * @param $model
     *
     * @return string
     */
    private function prepareErrorMsg($modelName, $model, $action)
    {
// Errors occurred
        $msg = "<h1>Error</h1>";
        $msg .= sprintf("Couldn't %s model <b>%s</b>", $action, $modelName);
        $msg .= "<ul>";
        foreach ($model->errors as $attribute => $attr_errors) {
            $msg .= "<li>Attribute: $attribute</li>";
            $msg .= "<ul>";
            foreach ($attr_errors as $attr_error) {
                $msg .= "<li>$attr_error</li>";
            }
            $msg .= "</ul>";
        }
        $msg .= "</ul>";

        return $msg;
    }

    /**
     * @param $modelName
     * @param $id
     *
     * @return array
     */
    public function delete($modelName, $id)
    {
        switch ($modelName) {
            // Load the respective model
            case 'user':
                $model = \User::model()->findByPk($id);
                break;
            case 'company':
                $model = \Company::model()->findByPk($id);
                break;
            default:
                return [
                    'statusCode' => 501,
                    'data'       =>
                        sprintf('Error: Mode <b>delete</b> is not implemented for model <b>%s</b>',
                            $modelName
                        )
                ];

        }
        // Was a model found? If not, raise an error
        if ($model === null) {

            return [
                'statusCode' => 400,
                'data'       => sprintf("Error: Didn't find any model <b>%s</b> with ID <b>%s</b>.",
                    $modelName, $id
                )
            ];
        }

        // Delete the model
        $num = $model->delete();
        if ($num > 0) {
            $statusCode = 200;
            $resultData = $num;
        }    //this is the only way to work with backbone
        else {
            $statusCode = 500;
            $resultData = sprintf("Error: Couldn't delete model <b>%s</b> with ID <b>%s</b>.",
                $modelName, $id
            );
        }

        return ['statusCode' => $statusCode, 'data' => $resultData];
    }
}