<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use Crenspire\Yii2Inertia\Inertia;
use Da\User\Helper\AuthHelper;
use Da\User\Model\Permission;
use Da\User\Search\PermissionSearch;
use Da\User\Service\AuthItemEditionService;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class PermissionController extends BaseController
{
    protected $authHelper;

    /**
     * {@inheritdoc}
     * @throws InvalidConfigException
     */
    public function __construct($id, $module, $config = [])
    {
        // Get AuthHelper instance
        $this->authHelper = Yii::createObject(AuthHelper::class);
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
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
     * @return string
     */
    protected function getModelClass(): string
    {
        return Permission::class;
    }

    /**
     * @return string
     */
    protected function getSearchModelClass(): string
    {
        return PermissionSearch::class;
    }

    /**
     * Helper method to create instances
     * @param string $class
     * @param array $params
     * @param array $config
     * @return object
     * @throws InvalidConfigException
     */
    protected function make(string $class, array $params = [], array $config = []): object
    {
        return Yii::createObject(array_merge(['class' => $class], $config), $params);
    }

    /**
     * @param string $name
     * @return Permission
     * @throws NotFoundHttpException
     */
    protected function getItem(string $name): Permission
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
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        /** @var PermissionSearch $searchModel */
        $searchModel = $this->make($this->getSearchModelClass());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        // Get permissions data
        $permissions = [];
        foreach ($dataProvider->getModels() as $permission) {
            /* @var \yii\rbac\Permission $permission */
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
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionView(string $name): Response
    {
        /* @var \yii\rbac\Permission $authItem */
        $authItem = $this->getItem($name);
        /** @var Permission $model */
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'update', 'item' => $authItem]);

        // Get assigned items (child permissions)
        $assignedItems = [];
        foreach ($authItem->children as $child) {
            /** @var Item $child */
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
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCreate(): Response
    {
        /** @var Permission $model */
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'create']);

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            // Convert 'none' to empty string for ruleName
            if (isset($requestData['ruleName']) && $requestData['ruleName'] === 'none') {
                $requestData['ruleName'] = '';
            }

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
                    'rule_name' => $model->ruleName ?: 'none',
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // GET request - show an empty form
        return Inertia::render('Permission/Form', [
            'permission' => [
                'name' => '',
                'description' => '',
                'rule_name' => 'none',
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
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate(string $name): Response
    {
        /* @var \yii\rbac\Permission $authItem */
        $authItem = $this->getItem($name);
        /** @var Permission $model */
        $model = $this->make($this->getModelClass(), [], ['scenario' => 'update', 'item' => $authItem]);

        if (Yii::$app->request->isPost || Yii::$app->request->isPut) {
            $requestData = Yii::$app->request->bodyParams ?: Yii::$app->request->post();

            // Convert 'none' to empty string for ruleName
            if (isset($requestData['ruleName']) && $requestData['ruleName'] === 'none') {
                $requestData['ruleName'] = '';
            }

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
                    'rule_name' => $model->ruleName ?: 'none',
                    'old_name' => $name,
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // GET request - show a form with current data
        return Inertia::render('Permission/Form', [
            'permission' => [
                'name' => $model->name,
                'description' => $model->description,
                'rule_name' => $model->ruleName ?: 'none',
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
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(string $name): Response
    {
        /** @var \yii\rbac\Permission $item */
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
    protected function formatUnassignedItems(array $items): array
    {
        $formatted = [];
        foreach ($items as $item) {
            /** @var Item $item */
            $formatted[] = [
                'name' => $item->name,
                'type' => $item->type === 1 ? 'role' : 'permission',
                'description' => $item->description,
            ];
        }
        return $formatted;
    }

    /**
     * Get a list of available rules.
     *
     * @return array
     */
    protected function getRulesList(): array
    {
        $rules = Yii::$app->authManager->getRules();
        $rulesList = [['value' => 'none', 'label' => 'No rule']];
        foreach ($rules as $rule) {
            $rulesList[] = [
                'value' => $rule->name,
                'label' => $rule->name,
            ];
        }
        return $rulesList;
    }
}
