<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use Crenspire\Yii2Inertia\Inertia;
use app\controllers\BaseController;

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
     * @return string
     */
    public function actionIndex()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Index', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->name,
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
     * @return string
     */
    public function actionProfile()
    {
        $user = Yii::$app->user->identity;

        if (Yii::$app->request->isPost) {
            // Handle profile update
            $data = Yii::$app->request->post();
            $user->name = $data['name'] ?? $user->name;
            $user->email = $data['email'] ?? $user->email;
            
            if ($user->validate() && $user->save()) {
                return Inertia::location('/dashboard/profile');
            }
        }

        return Inertia::render('Dashboard/Profile', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'errors' => $user->errors ?? [],
        ]);
    }

    /**
     * Settings action.
     *
     * @return string
     */
    public function actionSettings()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Settings', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->name,
                'email' => $identity->email,
            ],
        ]);
    }

    /**
     * Billing action.
     *
     * @return string
     */
    public function actionBilling()
    {
        $identity = Yii::$app->user->identity;
        return Inertia::render('Dashboard/Billing', [
            'user' => [
                'id' => $identity->id,
                'name' => $identity->name,
                'email' => $identity->email,
            ],
        ]);
    }
}

