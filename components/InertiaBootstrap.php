<?php

namespace app\components;

use Yii;
use yii\base\BootstrapInterface;
use Crenspire\Yii2Inertia\Inertia;

/**
 * Bootstrap component to share CSRF token and user data with all Inertia responses
 */
class InertiaBootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        // Share CSRF token with all Inertia responses
        // Use a closure to ensure we get a fresh token for each request
        Inertia::share('csrfToken', function () use ($app) {
            // Always get the current CSRF token for the request
            return $app->request->csrfToken;
        });

        Inertia::share('csrfParam', function () use ($app) {
            // Always get the current CSRF param name
            return $app->request->csrfParam;
        });

            // Share user data if authenticated
            Inertia::share('user', function () {
                $user = Yii::$app->user->identity;
                if ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->username,
                        'email' => $user->email,
                    ];
                }
                return null;
            });
    }
}

