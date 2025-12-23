<?php

use yii\rbac\Rule;
/*
 * Rule: Validasi Status
 *
 */
class StatusTransitionRule extends Rule
{
    public $name = 'validStatusTransition';

    public function execute($user, $item, $params)
    {
        $model = $params['model'];
        $newStatus = $params['newStatus'];

        $allowed = [
            'draft'      => ['submitted'],
            'submitted'  => ['verified', 'rejected'],
            'verified'   => ['approved'],
            'approved'   => ['distributed'],
            'distributed'=> ['completed'],
        ];

        return in_array($newStatus, $allowed[$model->status] ?? []);
    }
}