<?php

namespace app\models;

use Da\User\Search\PermissionSearch as BasePermissionSearch;
use yii\data\ArrayDataProvider;
use yii\rbac\ManagerInterface;
use Yii;

class PermissionSearch extends BasePermissionSearch
{
    public $search;
    public $rule_name;
    public $created_at_from;
    public $created_at_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['search', 'rule_name'], 'safe'],
            [['created_at_from', 'created_at_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function search($params)
    {
        /* @var ManagerInterface $authManager */
        $authManager = Yii::$app->authManager;
        $permissions = $authManager->getPermissions();

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return new ArrayDataProvider([
                'allModels' => [],
            ]);
        }

        $filteredPermissions = [];
        foreach ($permissions as $name => $permission) {
            $match = true;

            // Filter by search (name or description)
            if ($this->search) {
                $searchLower = strtolower($this->search);
                if (
                    (isset($permission->name) && strtolower($permission->name) === $searchLower) ||
                    (isset($permission->description) && strtolower($permission->description) === $searchLower)
                ) {
                    // Exact match, prioritize
                } elseif (
                    (isset($permission->name) && str_contains(strtolower($permission->name), $searchLower)) ||
                    (isset($permission->description) && str_contains(strtolower($permission->description), $searchLower))
                ) {
                    // Partial match
                } else {
                    $match = false;
                }
            }

            // Filter by rule_name
            if ($match && $this->rule_name && $this->rule_name !== 'all') {
                if ($this->rule_name === 'none') {
                    if ($permission->ruleName !== null) {
                        $match = false;
                    }
                } elseif ($permission->ruleName !== $this->rule_name) {
                    $match = false;
                }
            }

            // Filter by created_at_from
            if ($match && $this->created_at_from) {
                $createdAtTimestamp = $permission->createdAt;
                $filterFromTimestamp = strtotime($this->created_at_from);
                if ($createdAtTimestamp < $filterFromTimestamp) {
                    $match = false;
                }
            }

            // Filter by created_at_to
            if ($match && $this->created_at_to) {
                $createdAtTimestamp = $permission->createdAt;
                // Add one day to the 'to' date to include the entire day
                $filterToTimestamp = strtotime($this->created_at_to . ' +1 day');
                if ($createdAtTimestamp >= $filterToTimestamp) {
                    $match = false;
                }
            }

            if ($match) {
                $filteredPermissions[] = $permission;
            }
        }

        // Sort the filtered permissions
        if (isset($params['sort_by'])) {
            $sortBy = $params['sort_by'];
            $sortOrder = isset($params['sort_order']) && $params['sort_order'] === 'desc' ? SORT_DESC : SORT_ASC;

            usort($filteredPermissions, function ($a, $b) use ($sortBy, $sortOrder) {
                $valA = $a->$sortBy ?? null;
                $valB = $b->$sortBy ?? null;

                if ($valA === $valB) {
                    return 0;
                }
                if ($valA === null) {
                    return $sortOrder === SORT_ASC ? -1 : 1;
                }
                if ($valB === null) {
                    return $sortOrder === SORT_ASC ? 1 : -1;
                }

                return $sortOrder === SORT_ASC ? ($valA <=> $valB) : ($valB <=> $valA);
            });
        } else {
            // Default sort by name ascending
            usort($filteredPermissions, function ($a, $b) {
                return strcasecmp($a->name, $b->name);
            });
        }


        $dataProvider = new ArrayDataProvider([
            'allModels' => $filteredPermissions,
            'pagination' => [
                'pageSize' => 20, // Default page size
            ],
            'sort' => [
                'attributes' => ['name', 'description', 'ruleName', 'createdAt', 'updatedAt'],
                'defaultOrder' => ['name' => SORT_ASC],
            ],
        ]);

        // Apply pagination after sorting
        if (isset($params['page'])) {
            $dataProvider->pagination->setPage($params['page'] - 1);
        }
        if (isset($params['per_page'])) {
            $dataProvider->pagination->setPageSize($params['per_page']);
        }

        return $dataProvider;
    }
}
