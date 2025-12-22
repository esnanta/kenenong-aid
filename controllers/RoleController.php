<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\AuthItem;
use Crenspire\Yii2Inertia\Inertia;
use Da\User\Helper\AuthHelper;
use Da\User\Model\Role;
use Da\User\Search\RoleSearch;
use Da\User\Service\AuthItemEditionService;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Module;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
class RoleController extends BaseController
{
    protected AuthHelper $authHelper; // Added type declaration

    /**
     * @param string $id
     * @param Module $module
     * @param array $config
     * @throws InvalidConfigException
     */
    public function __construct($id, Module $module, $config = [])
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
     * Returns the model class name.
     * @return string
     */
    protected function getModelClass(): string
    {
        return Role::class;
    }

    /**
     * Returns the search model class name.
     * @return string
     */
    protected function getSearchModelClass(): string
    {
        return RoleSearch::class;
    }

    /**
     * Helper method to create instances
     * @throws InvalidConfigException
     */
    protected function make($class, $params = [], $config = []): object
    {
        return Yii::createObject(array_merge(['class' => $class], $config), $params);
    }

    /**
     * Retrieves an AuthItem (Role) by its name.
     *
     * @param string $name The name of the role.
     * @return \yii\rbac\Role
     * @throws NotFoundHttpException if the role is not found.
     */
    protected function getItem(string $name)
    {
        $authItem = $this->authHelper->getRole($name);
        if ($authItem !== null) {
            return $authItem;
        }

        throw new NotFoundHttpException('Role not found.');
    }

    /**
     * Lists all roles.
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        $searchModel = $this->make($this->getSearchModelClass());
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        // Get roles data
        $roles = [];
        foreach ($dataProvider->getModels() as $role) {
            $roles[] = [
                'name' => $role['name'],
                'description' => $role['description'],
                'rule_name' => $role['rule_name'],
            ];
        }

        return Inertia::render('Role/Index', [
            'roles' => $roles,
            'pagination' => [
                'current_page' => $dataProvider->pagination ? $dataProvider->pagination->getPage() + 1 : 1,
                'per_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageSize() : 20,
                'total' => $dataProvider->totalCount,
                'last_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageCount() : 1,
            ],
            'filters' => Yii::$app->request->get(),
            'sort' => [
                'sort_by' => Yii::$app->request->get('sort_by'),
                'sort_order' => Yii::$app->request->get('sort_order'),
            ],
        ]);
    }

    /**
     * Displays a single role.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionView(string $name): Response
    {
        $authItem = $this->getItem($name);

        // Get assigned items (child roles and permissions)
        $assignedItems = [];
        $children = Yii::$app->authManager->getChildren($name);
        /** @var AuthItem $child */
        foreach ($children as $child) {
            $assignedItems[] = [
                'name' => $child->name,
                'type' => $child->type === 1 ? 'role' : 'permission',
                'description' => $child->description,
            ];
        }

        return Inertia::render('Role/View', [
            'role' => [
                'name' => $authItem->name,
                'description' => $authItem->description,
                'rule_name' => $authItem->ruleName,
                'children' => $assignedItems,
            ],
        ]);
    }

    /**
     * Creates a new role.
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCreate(): Response
    {
        /** @var Role $model */
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
            return Inertia::render('Role/Form', [
                'role' => [
                    'name' => $model->name ?? '',
                    'description' => $model->description ?? '',
                    'rule_name' => $model->ruleName ?? 'none',
                    'old_name' => null, // Always include old_name, set to null for new roles
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // GET request - show an empty form
        return Inertia::render('Role/Form', [
            'role' => [
                'name' => '',
                'description' => '',
                'rule_name' => 'none',
                'old_name' => null, // Always include old_name, set to null for new roles
            ],
            'errors' => [],
            'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
            'rules' => $this->getRulesList(),
        ]);
    }

    /**
     * Updates an existing role.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate(string $name): Response
    {
        $authItem = $this->getItem($name);
        /** @var Role $model */
        $model = $this->make(
            $this->getModelClass(),
            [],
            ['scenario' => 'update', 'item' => $authItem]
        );

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
            return Inertia::render('Role/Form', [
                'role' => [
                    'name' => $model->name ?? '',
                    'description' => $model->description ?? '',
                    'rule_name' => $model->ruleName ?: 'none',
                    'old_name' => $name,
                    'children' => $model->children ?? [],
                ],
                'errors' => $model->errors,
                'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
                'rules' => $this->getRulesList(),
            ]);
        }

        // Get assigned children
        $assignedChildren = [];
        $authManager = Yii::$app->authManager;
        foreach ($authManager->getChildren($authItem->name) as $child) {
            $assignedChildren[] = $child->name;
        }

        // GET request - show a form with current data
        return Inertia::render('Role/Form', [
            'role' => [
                'name' => $authItem->name,
                'description' => $authItem->description,
                'rule_name' => $authItem->ruleName ?: 'none',
                'old_name' => $name,
                'children' => $assignedChildren,
            ],
            'errors' => [],
            'unassignedItems' => $this->formatUnassignedItems($this->authHelper->getUnassignedItems($model)),
            'rules' => $this->getRulesList(),
        ]);
    }

    /**
     * Deletes an existing role.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(string $name): Response
    {
        $item = $this->getItem($name);

        if ($this->authHelper->remove($item)) {
            Yii::$app->getSession()->setFlash('success', 'Role successfully deleted.');
        } else {
            Yii::$app->getSession()->setFlash('error', 'Unable to delete role.');
        }

        return Inertia::location('/roles');
    }

    /**
     * Format unassigned items for frontend.
     *
     * @param array $items Array with format ['name' => 'label']
     * @return array
     */
    protected function formatUnassignedItems(array $items): array
    {
        $formatted = [];
        $authManager = Yii::$app->authManager;

        foreach ($items as $name => $label) {
            // Get the actual item object from the auth manager
            $item = $authManager->getPermission($name) ?? $authManager->getRole($name);

            if ($item !== null) {
                $formatted[] = [
                    'name' => $item->name,
                    'type' => $item->type === 1 ? 'role' : 'permission',
                    'description' => $item->description ?? '',
                    'label' => $label,
                ];
            }
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
