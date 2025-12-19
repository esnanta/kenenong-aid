<?php

namespace app\controllers;

use app\models\Profile;
use Da\User\Controller\RegistrationController as BaseRegistrationController;
use Crenspire\Yii2Inertia\Inertia;
use Da\User\Form\RegistrationForm;
use Da\User\Form\ResendForm;
use Yii;
use yii\web\Response;

/**
 * RegistrationController handles user registration
 * Extends yii2-usuario RegistrationController
 */
class RegistrationController extends BaseRegistrationController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Allow guest access to registration
        if (isset($behaviors['access'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['register', 'connect', 'resend', 'confirm'],
                    'roles' => ['?'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Displays the registration page using Inertia.js
     *
     * @return Response|array
     */
    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        /** @var \Da\User\Module $module */
        $module = $this->module;

        if (!$module->enableRegistration) {
            Yii::$app->session->setFlash('error', 'Registration is disabled.');
            return Inertia::location('/login');
        }

        /** @var RegistrationForm $model */
        $model = $this->make(RegistrationForm::class);

        $requestData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && !empty($requestData)) {
            // Load data without wrapper
            if ($model->load($requestData, '')) {
                if ($user = $model->register()) {
                    // Create profile with name if provided
                    if (!empty($requestData['name'])) {
                        $profile = new Profile();
                        $profile->user_id = $user->id;
                        $profile->name = $requestData['name'];
                        $profile->save();
                    }

                    // Successful registration
                    Yii::$app->session->setFlash('success',
                        $module->enableEmailConfirmation
                            ? 'Your account has been created. Please check your email to confirm your registration.'
                            : 'Your account has been created successfully. You can now log in.'
                    );

                    return Inertia::location('/login');
                }
            }

            // Registration failed - return form with errors
            return Inertia::render('Auth/Register', [
                'form' => [
                    'email' => $requestData['email'] ?? '',
                    'username' => $requestData['username'] ?? '',
                    'name' => $requestData['name'] ?? '',
                ],
                'errors' => $model->errors,
                'enableEmailConfirmation' => $module->enableEmailConfirmation,
            ]);
        }

        // GET request - show empty registration form
        return Inertia::render('Auth/Register', [
            'form' => [
                'email' => '',
                'username' => '',
                'name' => '',
                'password' => '',
            ],
            'errors' => [],
            'enableEmailConfirmation' => $module->enableEmailConfirmation,
        ]);
    }

    /**
     * Confirms a user account
     *
     * @param int $id
     * @param string $code
     * @return Response
     */
    public function actionConfirm($id, $code)
    {
        /** @var \app\models\User $user */
        $user = $this->finder->findUserById($id);

        if ($user === null || $user->confirmed_at !== null) {
            Yii::$app->session->setFlash('error', 'Invalid or expired confirmation link.');
            return Inertia::location('/login');
        }

        /** @var \Da\User\Model\Token $token */
        $token = $this->finder->findTokenByParams($id, $code, \Da\User\Model\Token::TYPE_CONFIRMATION);

        if ($token === null || $token->isExpired) {
            Yii::$app->session->setFlash('error', 'Invalid or expired confirmation link.');
            return Inertia::location('/login');
        }

        if ($user->confirm()) {
            Yii::$app->session->setFlash('success', 'Your account has been confirmed successfully. You can now log in.');
        } else {
            Yii::$app->session->setFlash('error', 'An error occurred while confirming your account.');
        }

        return Inertia::location('/login');
    }

    /**
     * Resends confirmation email
     *
     * @return Response|array
     */
    public function actionResend()
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        /** @var ResendForm $model */
        $model = $this->make(ResendForm::class);

        $requestData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && !empty($requestData)) {
            if ($model->load($requestData, '') && $model->resend()) {
                Yii::$app->session->setFlash('success', 'A new confirmation email has been sent. Please check your inbox.');
                return Inertia::location('/login');
            }

            return Inertia::render('Auth/Resend', [
                'form' => [
                    'email' => $requestData['email'] ?? '',
                ],
                'errors' => $model->errors,
            ]);
        }

        return Inertia::render('Auth/Resend', [
            'form' => [
                'email' => '',
            ],
            'errors' => [],
        ]);
    }
}

