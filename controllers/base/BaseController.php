<?php

namespace app\controllers\base;

use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\base\InvalidConfigException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;

/**
 * Base controller that handles CSRF errors for Inertia requests
 */
class BaseController extends Controller
{
    /**
     * @throws ForbiddenHttpException
     */
    protected function checkAccess(string $permission, $model = null): void
    {
        if (!Yii::$app->user->can($permission, $model ? ['model' => $model] : [])) {
            throw new ForbiddenHttpException(
                Yii::t('app', 'Access Denied! You do not have permission to access this page.')
            );
        }
    }

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     * @throws BadRequestHttpException
     */
    public function beforeAction($action): bool
    {
        // For Inertia JSON requests, ensure CSRF token is read from the JSON body
        $isInertiaRequest = (Yii::$app->request->isPost || Yii::$app->request->isPut || Yii::$app->request->isPatch) && 
            (Yii::$app->request->headers->get('X-Inertia') !== null || 
             Yii::$app->request->headers->get('X-Inertia-Version') !== null);
        
        if ($isInertiaRequest && Yii::$app->request->contentType === 'application/json') {
            // Parse JSON body and populate POST data for Yii2
            $rawBody = Yii::$app->request->rawBody;
            if (!empty($rawBody)) {
                $jsonData = json_decode($rawBody, true);
                if ($jsonData && is_array($jsonData)) {
                    // For POST requests, populate $_POST with all JSON data
                    if (Yii::$app->request->isPost) {
                        $_POST = array_merge($_POST, $jsonData);
                    }
                    // For PUT/PATCH requests, populate bodyParams
                    if (Yii::$app->request->isPut || Yii::$app->request->isPatch) {
                        Yii::$app->request->setBodyParams(array_merge(
                            Yii::$app->request->getBodyParams(),
                            $jsonData
                        ));
                    }
                }
            }
        }
        
        try {
            return parent::beforeAction($action);
        } catch (BadRequestHttpException $e) {
            // If it's a CSRF error for Inertia request, return Inertia response
            if ($isInertiaRequest && 
                strpos($e->getMessage(), 'Unable to verify your data submission') !== false) {
                
                // Get the current route to determine which page to render
                $route = Yii::$app->request->pathInfo ?: 'home/index';
                
                // Try to determine the page component from the route
                $pageComponent = $this->getPageComponentFromRoute($route);
                
                // Return Inertia response with errors
                $response = Inertia::render($pageComponent, [
                    'errors' => [
                        'message' => 'Unable to verify your data submission. Please refresh the page and try again.',
                    ],
                ]);
                $response->send();
                return false; // Stop execution
            }
            
            // Re-throw if it's not a CSRF error or not an Inertia request
            throw $e;
        }
    }

    /**
     * Get Inertia page component name from the route
     * @param string $route
     * @return string
     */
    protected function getPageComponentFromRoute(string $route): string
    {
        // Map routes to Inertia page components
        $routeMap = [
            'auth/login' => 'Auth/Login',
            'auth/register' => 'Auth/Register',
            'auth/forgot-password' => 'Auth/ForgotPassword',
            'auth/reset-password' => 'Auth/ResetPassword',
            'dashboard' => 'Dashboard/Index',
            'dashboard/profile' => 'Dashboard/Profile',
            'dashboard/settings' => 'Dashboard/Settings',
            'dashboard/billing' => 'Dashboard/Billing',
            'users' => 'Users/Index',
            'users/create' => 'Users/Form',
            'users/<id:\d+>' => 'Users/View',
            'users/<id:\d+>/edit' => 'Users/Form',
            'disaster-statuses' => 'DisasterStatus/Index',
            'disaster-statuses/create' => 'DisasterStatus/Form',
            'disaster-statuses/<id:\d+>/edit' => 'DisasterStatus/Form',
            'disaster-statuses/<id:\d+>' => 'DisasterStatus/View',
            'disaster-types' => 'DisasterType/Index',
            'disaster-types/create' => 'DisasterType/Form',
            'disaster-types/<id:\d+>/edit' => 'DisasterType/Form',
            'disaster-types/<id:\d+>' => 'DisasterType/View',
            '' => 'Home',
            'home/index' => 'Home',
        ];
        
        // Handle dynamic routes with IDs
        if (preg_match('/^users\/(\d+)$/', $route, $matches)) {
            return 'Users/View';
        }
        if (preg_match('/^users\/(\d+)\/edit$/', $route, $matches)) {
            return 'Users/Form';
        }
        
        if (preg_match('/^disaster-statuses\/(\d+)$/', $route, $matches)) {
            return 'DisasterStatus/View';
        }
        if (preg_match('/^disaster-statuses\/(\d+)\/edit$/', $route, $matches)) {
            return 'DisasterStatus/Form';
        }

        if (preg_match('/^disaster-types\/(\d+)$/', $route, $matches)) {
            return 'DisasterType/View';
        }
        if (preg_match('/^disaster-types\/(\d+)\/edit$/', $route, $matches)) {
            return 'DisasterType/Form';
        }
        
        return $routeMap[$route] ?? 'Home';
    }
}
