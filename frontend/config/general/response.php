<?php
/**
 * App response configuration array.
 *
 * @author     SIXELIT <sixelit.com>
 */

use yii\web\Response;

return [
    'charset' => 'UTF-8',
    'format' => Response::FORMAT_JSON,
    'on beforeSend' => function ($event) {
        $response = $event->sender;

        if ($response->format == Response::FORMAT_HTML) {
            return;
        }

        $payload = [];
        $payload['code'] = $response->statusCode;
        $payload['name'] = $response->statusText;

        if (isset($response->data)) {
            if (isset($response->data['code']) && isset($response->data['message'])) {
                if ($response->data['message']) {
                    $payload['message'] = $response->data['message'];
                }
            } else {
                if ($response->isSuccessful) {
                    $payload['data'] = $response->data;
                } else {
                    $payload['errors'] = $response->data;
                }
            }
        }

        $response->data = $payload;
    }
];