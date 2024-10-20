<?php

namespace app\controllers;

use Yii;
use yii\web\Response;

trait RenderJSONTrait
{
    /**
     * @param $status
     * @param $message
     * @param array $data
     * @return array
     */
    public function renderJSON($status, $message, $data = null)
    {
        $json = [
            'status' => $status,
            'message' => $message,
        ];

        if ($data && is_array($data)) {
            $json = array_merge($json, $data);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $json;
    }
}