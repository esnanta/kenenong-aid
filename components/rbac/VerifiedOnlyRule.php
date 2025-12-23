<?php

use yii\rbac\Rule;

/*
 * Rule: Distribusi hanya dari data terverifikasi
 * Dipakai oleh:
 *          - aid-create
 *          - aid-dispatch
 */
class VerifiedOnlyRule extends Rule
{
    public $name = 'verifiedOnly';

    public function execute($user, $item, $params): bool
    {
        return $params['incident']->status === 'verified';
    }
}