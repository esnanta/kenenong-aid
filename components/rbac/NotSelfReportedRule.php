<?php

use yii\rbac\Rule;

/*
 * Rule: Tidak boleh verifikasi laporan sendiri
 * Dipakai oleh:
 *              - incident-verify
 *              - verification-create
 */
class NotSelfReportedRule extends Rule
{
    public $name = 'notSelfReported';

    public function execute($user, $item, $params)
    {
        return $params['model']->created_by !== $user;
    }
}