<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Da\User\Form\LoginForm;
use Da\User\Form\RegistrationForm;
use Da\User\Model\User;
use Da\User\Service\UserRegisterService;
use Da\User\Helper\SecurityHelper;
use Da\User\Factory\MailFactory;
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

        /** @var LoginForm $model */
        $model = Yii::createObject(LoginForm::class);

        if (Yii::$app->request->isPost) {
            try {
                if ($model->load(Yii::$app->request->post(), '')) {
                    if ($model->login()) {
                        // Update last login info
                        $model->getUser()->updateAttributes([
                            'last_login_at' => time(),
                            'last_login_ip' => Yii::$app->request->getUserIP(),
                        ]);

                        // Login successful - redirect to dashboard
                        return Inertia::location('/dashboard');
                    }
                    // Login failed - model has errors, return form with errors
                }
            } catch (\Exception $e) {
                // Catch any exceptions and add them as errors
                Yii::error('Login error: ' . $e->getMessage(), 'application');
                $model->addError('login', 'An error occurred during login. Please try again.');
            }
            
            // If we get here, either load failed or login failed - return form with errors
            return Inertia::render('Auth/Login', [
                'model' => [
                    'login' => $model->login ?? '',
                    'rememberMe' => $model->rememberMe ?? false,
                ],
                'errors' => $model->errors, // Pass validation errors
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Auth/Login', [
            'model' => [
                'login' => '',
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

        $module = Yii::$app->getModule('user');

        /** @var RegistrationForm $form */
        $form = Yii::createObject(RegistrationForm::class);

        if (Yii::$app->request->isPost) {
            if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
                // Create user instance from form
                /** @var User $user */
                $user = Yii::createObject(User::class);

                // Set user attributes from form
                $user->username = $form->username;
                $user->email = $form->email;
                $user->password = $form->password; // Will be hashed by the service
                $user->setScenario('register');

                // Create mail service
                $mailService = MailFactory::makeWelcomeMailerService($user);

                // Create security helper
                $securityHelper = Yii::createObject(SecurityHelper::class);

                // Register user using yii2-usuario service
                /** @var UserRegisterService $registerService */
                $registerService = new UserRegisterService($user, $mailService, $securityHelper);

                if ($registerService->run()) {
                    // Auto-login if email confirmation is disabled
                    if (!$module->enableEmailConfirmation) {
                        Yii::$app->user->login($user, 3600 * 24 * 30); // 30 days
                        return Inertia::location('/dashboard');
                    }

                    // Redirect to login with success message
                    Yii::$app->session->setFlash(
                        'success',
                        Yii::t('usuario', 'Your account has been created. Please check your email for confirmation.')
                    );
                    return Inertia::location('/login');
                }

                // Registration failed
                $form->addError('email', 'Registration failed. Please try again.');
            }

            // Return form with errors if validation failed
            return Inertia::render('Auth/Register', [
                'model' => [
                    'username' => $form->username ?? '',
                    'email' => $form->email ?? '',
                ],
                'errors' => $form->errors,
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Auth/Register', [
            'model' => [
                'username' => '',
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

