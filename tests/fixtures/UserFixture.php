<?php

namespace app\fixtures;

use yii\test\ActiveFixture;

/**
 * User fixture
 *
 * Provides test user data for authentication and authorization tests
 */
class UserFixture extends ActiveFixture
{
    /**
     * @var string the table name
     */
    public $modelClass = 'app\models\User';

    /**
     * @var string path to data file
     */
    public $dataFile = '@tests/_data/user.php';
}

