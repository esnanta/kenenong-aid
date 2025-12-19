<?php

namespace app\controllers;

use Da\User\Controller\RecoveryController as BaseRecoveryController;
use Da\User\Form\RecoveryForm;
use Da\User\Service\ResetPasswordService;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;

/**
 * RecoveryController handles password recovery functionality
 * Extends yii2-usuario RecoveryController
 */
class RecoveryController extends BaseRecoveryController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // Allow guest access to recovery actions
        if (isset($behaviors['access'])) {
            $behaviors['access']['rules'] = [
                [
                    'allow' => true,
                    'actions' => ['request', 'reset'],
                    'roles' => ['?'],
                ],
            ];
        }

        return $behaviors;
    }

    /**
     * Displays the password recovery request page
     *
     * @return Response|array
     */
    public function actionRequest()
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        /** @var RecoveryForm $model */
        $model = $this->make(RecoveryForm::class, [], ['scenario' => RecoveryForm::SCENARIO_REQUEST]);

        $requestData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && !empty($requestData)) {
            if ($model->load($requestData, '') && $model->sendRecoveryMessage()) {
                Yii::$app->session->setFlash('success',
                    'If the email address you entered is registered, you will receive a password reset link shortly.'
                );
                return Inertia::location('/login');
            }

            // Recovery request failed - return form with errors
            return Inertia::render('Auth/ForgotPassword', [
                'form' => [
                    'email' => $requestData['email'] ?? '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show empty recovery form
        return Inertia::render('Auth/ForgotPassword', [
            'form' => [
                'email' => '',
            ],
            'errors' => [],
        ]);
    }

    /**
     * Displays the password reset page
     *
     * @param int $id
     * @param string $code
     * @return Response|array
     * @throws NotFoundHttpException
     */
    public function actionReset($id, $code)
    {
        if (!Yii::$app->user->isGuest) {
            return Inertia::location('/dashboard');
        }

        /** @var \Da\User\Model\Token $token */
        $token = $this->tokenQuery->findToken($id, $code, \Da\User\Model\Token::TYPE_RECOVERY)->one();

        if ($token === null || $token->getIsExpired() || $token->user === null) {
            Yii::$app->session->setFlash('error', 'Invalid or expired password reset link.');
            return Inertia::location('/forgot-password');
        }

        /** @var RecoveryForm $model */
        $model = $this->make(RecoveryForm::class, [], ['scenario' => RecoveryForm::SCENARIO_RESET]);

        $requestData = Yii::$app->request->post();

        if (Yii::$app->request->isPost && !empty($requestData)) {
            if ($model->load($requestData, '')) {
                // Use ResetPasswordService to reset the password
                /** @var ResetPasswordService $resetPasswordService */
                $resetPasswordService = $this->make(ResetPasswordService::class, [$model->password, $token->user]);

                if ($resetPasswordService->run()) {
                    Yii::$app->session->setFlash('success', 'Your password has been reset successfully. You can now log in with your new password.');
                    return Inertia::location('/login');
                }
            }

            // Reset failed - return form with errors
            return Inertia::render('Auth/ResetPassword', [
                'form' => [
                    'password' => '',
                ],
                'errors' => $model->errors,
                'id' => $id,
                'code' => $code,
            ]);
        }

        // GET request - show password reset form
        return Inertia::render('Auth/ResetPassword', [
            'form' => [
                'password' => '',
            ],
            'errors' => [],
            'id' => $id,
            'code' => $code,
        ]);
    }
}

