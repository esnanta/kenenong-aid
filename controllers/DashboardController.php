<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\filters\AccessControl;

class DashboardController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Dashboard index action.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Index', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->profile->name ?? $identity->username,
                'email' => $identity->email,
            ],
            'stats' => [
                'totalUsers' => \app\models\User::find()->count(),
                'revenue' => 0,
                'growth' => 0,
                'activeUsers' => \app\models\User::find()->count(), // All non-deleted users are active
            ],
        ]);
    }

    /**
     * Profile action.
     *
     * @return \yii\web\Response
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;
        $profile = $user->profile;

        if (Yii::$app->request->isPost) {
            // Handle profile update
            $data = Yii::$app->request->post();

            if ($profile) {
                $profile->name = $data['name'] ?? $profile->name;
            }
            $user->email = $data['email'] ?? $user->email;
            
            if ($user->validate() && $user->save() && (!$profile || $profile->save())) {
                return Inertia::location('/dashboard/profile');
            }
        }

        return Inertia::render('Dashboard/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $profile->name ?? $user->username,
                'email' => $user->email,
            ],
            'errors' => array_merge($user->errors ?? [], $profile ? $profile->errors : []),
        ]);
    }

    /**
     * Settings action.
     *
     * @return \yii\web\Response
     */
    public function actionSettings()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Settings', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->profile->name ?? $identity->username,
                'email' => $identity->email,
            ],
        ]);
    }

    /**
     * Billing action.
     *
     * @return \yii\web\Response
     */
    public function actionBilling()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Billing', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->profile->name ?? $identity->username,
                'email' => $identity->email,
            ],
        ]);
    }
}

