<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\User;
use Crenspire\Yii2Inertia\Inertia;
use app\controllers\BaseController;

class AuthController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        $model = new LoginForm();

        if (Yii::$app->request->isPost) {
            try {
                if ($model->load(Yii::$app->request->post(), '')) {
                    if ($model->login()) {
                        // Login successful - redirect to dashboard
                        return Inertia::location('/dashboard');
                    }
                    // Login failed - model has errors, return form with errors
                }
            } catch (\Exception $e) {
                // Catch any exceptions and add them as errors
                Yii::error('Login error: ' . $e->getMessage(), 'application');
                $model->addError('email', 'An error occurred during login. Please try again.');
            }
            
            // If we get here, either load failed or login failed - return form with errors
            return Inertia::render('Auth/Login', [
                'model' => [
                    'email' => $model->email ?? '',
                    'rememberMe' => $model->rememberMe ?? false,
                ],
                'errors' => $model->errors, // Pass validation errors
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Auth/Login', [
            'model' => [
                'email' => '',
                'rememberMe' => false,
            ],
            'errors' => [],
        ]);
    }

    /**
     * Register action.
     *
     * @return Response|string
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard']);
        }

        $model = new User();

        if (Yii::$app->request->isPost) {
            // Use 'create' scenario for POST requests
            $model->scenario = 'create';
            
            if ($model->load(Yii::$app->request->post(), '')) {
                // Name and email are required, no username generation needed
                if ($model->validate() && $model->save()) {
                    Yii::$app->user->login($model, 3600 * 24 * 30); // 30 days
                    return Inertia::location('/dashboard');
                }
            }

            // Return form with errors if validation failed
            return Inertia::render('Auth/Register', [
                'model' => [
                    'fullName' => $model->name ?? '',
                    'email' => $model->email ?? '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Auth/Register', [
            'model' => [
                'fullName' => '',
                'email' => '',
            ],
            'errors' => [],
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        // Destroy the user session (this also clears remember me cookies)
        Yii::$app->user->logout();
        
        // Explicitly destroy the session to ensure it's cleared
        if (Yii::$app->has('session')) {
            Yii::$app->session->destroy();
        }
        
        // For Inertia requests, use Inertia::location which handles redirects properly
        if (Yii::$app->request->headers->get('X-Inertia')) {
            return Inertia::location('/');
        }
        
        // Use regular redirect for non-Inertia requests
        return $this->redirect(['/']);
    }

    /**
     * Forgot password action.
     *
     * @return Response|string
     */
    public function actionForgotPassword()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard']);
        }

        // TODO: Implement password reset logic
        return Inertia::render('Auth/ForgotPassword');
    }

    /**
     * Reset password action.
     *
     * @return Response|string
     */
    public function actionResetPassword($token = null)
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/dashboard']);
        }

        // TODO: Implement password reset logic
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
        ]);
    }
}

