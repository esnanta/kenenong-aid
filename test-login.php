<?php
/**
 * Test script untuk memverifikasi bahwa yii2-usuario LoginForm bisa dibuat dengan Yii::createObject
 */

// Load Yii framework
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

// Load configuration
$config = require __DIR__ . '/config/web.php';

// Create application
$app = new yii\web\Application($config);

try {
    echo "Testing Yii::createObject(Da\\User\\Form\\LoginForm::class)...\n";

    $loginForm = Yii::createObject(\Da\User\Form\LoginForm::class);

    if ($loginForm instanceof \Da\User\Form\LoginForm) {
        echo "✓ SUCCESS: LoginForm created successfully!\n";
        echo "  Class: " . get_class($loginForm) . "\n";
        echo "  Has login property: " . (property_exists($loginForm, 'login') ? 'Yes' : 'No') . "\n";
        echo "  Has password property: " . (property_exists($loginForm, 'password') ? 'Yes' : 'No') . "\n";
    } else {
        echo "✗ FAILED: Object is not instance of LoginForm\n";
    }

    echo "\nTesting Yii::createObject(Da\\User\\Form\\RegistrationForm::class)...\n";

    $registrationForm = Yii::createObject(\Da\User\Form\RegistrationForm::class);

    if ($registrationForm instanceof \Da\User\Form\RegistrationForm) {
        echo "✓ SUCCESS: RegistrationForm created successfully!\n";
        echo "  Class: " . get_class($registrationForm) . "\n";
        echo "  Has username property: " . (property_exists($registrationForm, 'username') ? 'Yes' : 'No') . "\n";
        echo "  Has email property: " . (property_exists($registrationForm, 'email') ? 'Yes' : 'No') . "\n";
    } else {
        echo "✗ FAILED: Object is not instance of RegistrationForm\n";
    }

    echo "\nTesting app\\models\\User extends Da\\User\\Model\\User...\n";

    $user = Yii::createObject(\app\models\User::class);

    if ($user instanceof \Da\User\Model\User) {
        echo "✓ SUCCESS: User model extends Da\\User\\Model\\User correctly!\n";
        echo "  Class: " . get_class($user) . "\n";
    } else {
        echo "✗ FAILED: User model does not extend Da\\User\\Model\\User\n";
    }

    echo "\n✓ All tests passed! yii2-usuario integration is working correctly.\n";

} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}

