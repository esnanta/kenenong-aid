<?php

namespace app\controllers;

use Yii;
use Da\User\Controller\AbstractAuthItemController;
use Da\User\Model\Permission;
use Da\User\Search\PermissionSearch;
use Da\User\Helper\AuthHelper;
use Da\User\Service\AuthItemEditionService;
use Da\User\Validator\AjaxRequestModelValidator;
use Crenspire\Yii2Inertia\Inertia;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;

class PermissionController extends BaseController
{
    protected $authHelper;

    /**
     * {@inheritdoc}
     */
    public function __construct($id, $module, $config = [])
    {
        // Get AuthHelper instance
        $this->authHelper = Yii::createObject(AuthHelper::class);
        parent::__construct($id, $module, $config);
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // Allow all authenticated users for now
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getModelClass()
    {
        return Permission::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSearchModelClass()
    {
        return PermissionSearch::class;
    }

    /**
     * Helper method to create instances
     */
    protected function make($class, $params = [], $config = [])
    {
        return Yii::createObject(array_merge(['class' => $class], $config), $params);
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotFoundHttpException
     */
    protected function getItem($name)
    {
        $authItem = $this->authHelper->getPermission($name);

        if ($authItem !== null) {
            return $authItem;
        }

        throw new NotFoundHttpException('Permission not found.');
    }

    /**
     * Lists all permissions.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $searchModel = $this->make($this->getSearchModelClass());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        // Get permissions data
        $permissions = [];
        foreach ($dataProvider->getModels() as $permission) {
            $permissions[] = [
                'name' => $permission->name,
                'description' => $permission->description,
                'rule_name' => $permission->ruleName,
                'created_at' => $permission->createdAt ? date('Y-m-d H:i:s', $permission->createdAt) : null,
                'updated_at' => $permission->updatedAt ? date('Y-m-d H:i:s', $permission->updatedAt) : null,
            ];
        }

        return Inertia::render('Permission/Index', [
            'permissions' => $permissions,
            'pagination' => [
                'current_page' => $dataProvider->pagination ? $dataProvider->pagination->getPage() + 1 : 1,
                'per_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageSize() : 20,
                'total' => $dataProvider->totalCount,
                'last_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageCount() : 1,
            ],
        ]);
    }

    /**
     * Displays a single permission.
     *
     * @param string $name
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($name)
    {
        $authItem = $this->getItem($name);
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'update', 'item' => $authItem]);

        // Get assigned items (child permissions)
        $assignedItems = [];
        foreach ($authItem->children as $child) {
            $assignedItems[] = [
                'name' => $child->name,
                'type' => $child->type === 1 ? 'role' : 'permission',
                'description' => $child->description,
            ];
        }

        return Inertia::render('Permission/View', [
            'permission' => [
                'name' => $model->name,
                'description' => $model->description,
                'rule_name' => $model->ruleName,
                'created_at' => $authItem->createdAt ? date('Y-m-d H:i:s', $authItem->createdAt) : null,
                'updated_at' => $authItem->updatedAt ? date('Y-m-d H:i:s', $authItem->updatedAt) : null,
                'children' => $assignedItems,
            ],
        ]);
    }

    /**
     * Creates a new permission.
     *
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        /** @var Permission $model */
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'create']);

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            if ($model->load($requestData, '')) {
                if ($this->make(AuthItemEditionService::class, [$model])->run()) {
                    // For Inertia requests, redirect to index
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            // Return form with errors
            return Inertia::render('Permission/Form', [
                'permission' => [
                    'name' => $model->name ?? '',
                    'description' => $model->description ?? '',
                    'rule_name' => $model->ruleName ?? '',
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Permission/Form', [
            'permission' => [
                'name' => '',
                'description' => '',
                'rule_name' => '',
            ],
            'errors' => [],
            'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
            'rules' => $this->getRulesList(),
        ]);
    }

    /**
     * Updates an existing permission.
     *
     * @param string $name
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($name)
    {
        $authItem = $this->getItem($name);
        /** @var Permission $model */
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'update', 'item' => $authItem]);

        if (Yii::$app->request->isPost || Yii::$app->request->isPut) {
            $requestData = Yii::$app->request->bodyParams ?: Yii::$app->request->post();

            if ($model->load($requestData, '')) {
                if ($this->make(AuthItemEditionService::class, [$model])->run()) {
                    // For Inertia requests, redirect to index
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            // Return form with errors
            return Inertia::render('Permission/Form', [
                'permission' => [
                    'name' => $model->name ?? '',
                    'description' => $model->description ?? '',
                    'rule_name' => $model->ruleName ?? '',
                    'old_name' => $name,
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // GET request - show form with current data
        return Inertia::render('Permission/Form', [
            'permission' => [
                'name' => $model->name,
                'description' => $model->description,
                'rule_name' => $model->ruleName,
                'old_name' => $name,
            ],
            'errors' => [],
            'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
            'rules' => $this->getRulesList(),
        ]);
    }

    /**
     * Deletes an existing permission.
     *
     * @param string $name
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($name)
    {
        $item = $this->getItem($name);

        if ($this->authHelper->remove($item)) {
            Yii::$app->getSession()->setFlash('success', 'Permission successfully deleted.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Unable to delete permission.');
        }

        return Inertia::location('/permission');
    }

    /**
     * Format unassigned items for frontend.
     *
     * @param array $items
     * @return array
     */
    protected function formatUnassignedItems($items)
    {
        $formatted = [];
        foreach ($items as $item) {
            $formatted[] = [
                'name' => $item->name,
                'type' => $item->type === 1 ? 'role' : 'permission',
                'description' => $item->description,
            ];
        }
        return $formatted;
    }

    /**
     * Get list of available rules.
     *
     * @return array
     */
    protected function getRulesList()
    {
        $rules = Yii::$app->authManager->getRules();
        $rulesList = [['value' => '', 'label' => 'No rule']];
        foreach ($rules as $rule) {
            $rulesList[] = [
                'value' => $rule->name,
                'label' => $rule->name,
            ];
        }
        return $rulesList;
    }
}

