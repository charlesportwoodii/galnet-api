<?php

namespace app\components;

use Yii;

/**
 * Event handler for response object
 */
class ResponseEvent extends yii\base\Event
{
	/**
	 * Before Send event handler
	 * @param yii\base\Event $event
	 */
	public function beforeSend($event)
	{
		$response = $event->sender;

        if (\Yii::$app->request->getIsOptions())
        {
            $response->statusCode = 200;
            $response->data = null;
        }

        if ($response->data !== null)
        {
            $data = $response->data;
            $data = isset($data['data']) ? $data['data'] : $data;

            $response->data = [
                'data'  => $data,
            ];

            // Handle and display errors in the API for easy debugging
            $exception = \Yii::$app->errorHandler->exception;
            if ($exception && get_class($exception) !== "yii\web\HttpException" && !is_subclass_of($exception, 'yii\web\HttpException') && YII_DEBUG)
            {
                $response->data['exception'] = [
                    'message'   => $exception->getMessage(),
                    'file'      => $exception->getFile(),
                    'line'      => $exception->getLine(),
                    'trace'     => $exception->getTraceAsString()
                ];
            }
        }
	}
}
