<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use app\models\User;

/**
 * Seed controller for populating initial data
 */
class SeedController extends Controller
{
    /**
     * Seeds admin user
     * @return int Exit code
     */
    public function actionAdmin()
    {
        $name = 'Admin User';
        $email = 'admin@example.com';
        $password = 'admin123';

        // Check if admin already exists
        $existingUser = User::findByEmail($email);
        if ($existingUser) {
            $this->stdout("Admin user already exists!\n", \yii\helpers\Console::FG_YELLOW);
            return ExitCode::OK;
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = $password;
        $user->scenario = 'register';

        if ($user->save()) {
            $this->stdout("Admin user created successfully!\n", \yii\helpers\Console::FG_GREEN);
            $this->stdout("Name: {$name}\n");
            $this->stdout("Email: {$email}\n");
            $this->stdout("Password: {$password}\n");
            return ExitCode::OK;
        } else {
            $this->stdout("Failed to create admin user:\n", \yii\helpers\Console::FG_RED);
            foreach ($user->errors as $attribute => $errors) {
                foreach ($errors as $error) {
                    $this->stdout("  - {$attribute}: {$error}\n");
                }
            }
            return ExitCode::DATAERR;
        }
    }
}
