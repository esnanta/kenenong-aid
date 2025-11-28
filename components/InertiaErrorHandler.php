<?php

namespace app\components;

use Yii;
use yii\web\ErrorHandler as BaseErrorHandler;
use yii\web\NotFoundHttpException;
use Crenspire\Yii2Inertia\Inertia;

/**
 * Custom error handler that renders React pages via Inertia for 4xx and 5xx errors
 */
class InertiaErrorHandler extends BaseErrorHandler
{
    /**
     * Renders the exception.
     * @param \Exception $exception the exception to be rendered.
     */
    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
            // reset parameters of response to avoid interference with partially created response data
            // in case the error occurred while sending the response.
            $response->isSent = false;
            $response->stream = null;
            $response->data = null;
            $response->content = null;
        } else {
            $response = new \yii\web\Response();
        }

        $response->setStatusCodeByException($exception);

        $statusCode = $exception->statusCode ?? 500;
        
        // Define which error codes should show the NotFound page
        // 400 errors are validation errors and should be handled by forms, not redirected
        $showNotFoundPage = in_array($statusCode, [401, 403, 404, 500, 502, 503, 504]);

        // For specific error codes, render React NotFound page via Inertia
        if ($showNotFoundPage) {
            $message = match($statusCode) {
                401 => 'Unauthorized - Please log in to continue',
                403 => 'Forbidden - You do not have permission to access this resource',
                404 => 'Page not found',
                500 => 'Internal server error',
                502 => 'Bad gateway',
                503 => 'Service unavailable',
                504 => 'Gateway timeout',
                default => 'An error occurred',
            };

            // Render Inertia page and send response
            $inertiaResponse = Inertia::render('NotFound', [
                'status' => $statusCode,
                'message' => $message,
            ]);
            
            // Inertia::render returns a Response object, send it directly
            $inertiaResponse->send();
            return;
        }

        // For 400 errors, completely skip the error handler
        // 400 errors should be handled by controllers and returned as Inertia responses with errors
        // If a 400 exception reaches here, it means it wasn't handled by the controller
        // In this case, we should not catch it - let it be handled by the normal request flow
        // The error handler should not interfere with 400 errors at all
        if ($statusCode === 400) {
            // Don't handle 400 errors here - they should be handled by controllers
            // Return early without rendering anything
            // This allows the exception to be handled by the normal request flow
            // Controllers should catch 400 errors and return Inertia responses with errors
            return;
        }

        // For other errors, use parent implementation
        parent::renderException($exception);
    }
}

