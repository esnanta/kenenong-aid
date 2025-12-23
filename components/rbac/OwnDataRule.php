<?php
namespace app\components\rbac;

use yii\rbac\Rule;
/*
 * Rule: Update hanya milik sendiri
 * Dipakai oleh:
 *          - incident-update-own
 *          - incident-view-own
 */
class OwnDataRule extends Rule
{
    public $name = 'isOwner';

    public function execute($user, $item, $params): bool
    {
        return isset($params['model']) &&
            $params['model']->created_by == $user;
    }
}