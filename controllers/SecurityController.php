<?php

namespace app\controllers;

use Da\User\Controller\SecurityController as BaseSecurityController;
use Crenspire\Yii2Inertia\Inertia;
use Da\User\Form\LoginForm;
use Yii;
use yii\web\Response;
use yii\base\InvalidConfigException;

/**
 * SecurityController handles login and logout functionality
 * Extends yii2-usuario SecurityController for authentication
 */
class SecurityController extends BaseSecurityController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        // Allow guest access to log in action
        if (isset($behaviors['access'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['login'],
                    'roles' => ['?'],
                ],
                [
                    'allow' => true,
                    'actions' => ['logout'],
                    'roles' => ['@'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Displays the login page using Inertia.js
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionLogin(): Response
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        /** @var LoginForm $model */
        $model = $this->make(LoginForm::class);

        $requestData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && !empty($requestData)) {
            // Load data without a wrapper
            if ($model->load($requestData, '')) {
                if ($model->login()) {
                    // Successful login - redirect to dashboard
                    return Inertia::location('/dashboard');
                }
            }

            // Login failed - return form with errors
            return Inertia::render('Auth/Login', [
                'form' => [
                    'login' => $requestData['login'] ?? '',
                    'rememberMe' => $requestData['rememberMe'] ?? false,
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show an empty login form
        return Inertia::render('Auth/Login', [
            'form' => [
                'login' => '',
                'rememberMe' => false,
            ],
            'errors' => [],
        ]);
    }

    /**
     * Logs out the current user
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
        }

        return Inertia::location('/login');
    }
}
