<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\web\NotFoundHttpException;

class HomeController extends BaseController
{
    /**
     * Custom error action that renders React 404 page
     * This handles all 4xx and 5xx errors and renders them via Inertia
     */
    public function actionError()
    {
        // Disable layout to ensure we only render Inertia content
        $this->layout = false;
        
        $exception = Yii::$app->errorHandler->exception;
        
        if ($exception === null) {
            $exception = new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }

        $statusCode = $exception->statusCode ?? 500;
        $message = $exception->getMessage() ?: Yii::t('yii', 'An internal server error occurred.');

        // Set the HTTP status code
        Yii::$app->response->statusCode = $statusCode;

        // Define which error codes should show the NotFound page
        // 400 errors are validation errors and should be handled by forms, not redirected
        $showNotFoundPage = in_array($statusCode, [401, 403, 404, 500, 502, 503, 504]);

        // For specific error codes, render React NotFound page
        if ($showNotFoundPage) {
            switch ($statusCode) {
                case 401:
                    $errorMessage = 'Unauthorized - Please log in to continue';
                    break;
                case 403:
                    $errorMessage = 'Forbidden - You do not have permission to access this resource';
                    break;
                case 404:
                    $errorMessage = 'Page not found';
                    break;
                case 500:
                    $errorMessage = 'Internal server error';
                    break;
                case 502:
                    $errorMessage = 'Bad gateway';
                    break;
                case 503:
                    $errorMessage = 'Service unavailable';
                    break;
                case 504:
                    $errorMessage = 'Gateway timeout';
                    break;
                default:
                    $errorMessage = 'An error occurred';
                    break;
            }

            return Inertia::render('NotFound', [
                'status' => $statusCode,
                'message' => $errorMessage,
            ]);
        }

        // For 400 errors, don't show error page - let them be handled by forms
        // 400 errors are validation errors that should be shown in forms via Inertia
        if ($statusCode === 400) {
            // Return the current page with errors - don't redirect to error page
            // The errors will be handled by Inertia's onError callbacks in forms
            return $this->redirect(Yii::$app->request->referrer ?: '/');
        }

        // For other status codes, show NotFound page
        return Inertia::render('NotFound', [
            'status' => $statusCode,
            'message' => $message,
        ]);
    }

    public function actionIndex()
    {
        $user = null;
        if (!Yii::$app->user->isGuest) {
            $identity = Yii::$app->user->identity;
            $user = [
                'id' => $identity->id,
                'name' => $identity->username,
                'email' => $identity->email,
            ];
        }
        
        return Inertia::render('Home', [
            'title' => 'Welcome',
            'user' => $user,
        ]);
    }
}