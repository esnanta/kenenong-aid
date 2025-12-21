<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use Crenspire\Yii2Inertia\Inertia;
use Da\User\Model\Rule;
use Da\User\Search\RuleSearch;
use Da\User\Service\AuthRuleEditionService;
use Da\User\Traits\AuthManagerAwareTrait;
use Da\User\Traits\ContainerAwareTrait;
use Yii;
use yii\base\InvalidConfigException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\rbac\Rule as RbacRule;

class RuleController extends BaseController
{
    use AuthManagerAwareTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
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
     * Helper method to create instances
     * @throws InvalidConfigException
     */
    protected function make($class, $params = [], $config = []): object
    {
        return Yii::createObject(array_merge(['class' => $class], $config), $params);
    }

    /**
     * Lists all rules.
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionIndex(): Response
    {
        /** @var RuleSearch $searchModel */
        $searchModel = $this->make(RuleSearch::class);
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        // Get rules data
        $rules = [];
        foreach ($dataProvider->getModels() as $rule) {
            $rules[] = [
                'name' => $rule->name,
                'class_name' => $rule->className,
            ];
        }

        return Inertia::render('Rule/Index', [
            'rules' => $rules,
            'pagination' => [
                'current_page' => $dataProvider->pagination ? $dataProvider->pagination->getPage() + 1 : 1,
                'per_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageSize() : 20,
                'total' => $dataProvider->totalCount,
                'last_page' => $dataProvider->pagination ? $dataProvider->pagination->getPageCount() : 1,
            ],
        ]);
    }

    /**
     * Displays a single rule.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionView(string $name): Response
    {
        $rule = $this->findRule($name);

        return Inertia::render('Rule/View', [
            'rule' => [
                'name' => $rule->name,
                'class_name' => get_class($rule),
            ],
        ]);
    }

    /**
     * Creates a new rule.
     *
     * @return Response
     * @throws InvalidConfigException
     */
    public function actionCreate(): Response
    {
        $model = $this->make(Rule::class, [], ['scenario' => 'create', 'className' => RbacRule::class]);

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            if ($model->load($requestData, '')) {
                if ($this->make(AuthRuleEditionService::class, [$model])->run()) {
                    // For Inertia requests, redirect to index
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            // Return form with errors
            return Inertia::render('Rule/Form', [
                'rule' => [
                    'name' => $model->name ?? '',
                    'class_name' => $model->className ?? '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show an empty form
        return Inertia::render('Rule/Form', [
            'rule' => [
                'name' => '',
                'class_name' => '',
            ],
            'errors' => [],
        ]);
    }

    /**
     * Updates an existing rule.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     * @throws InvalidConfigException
     */
    public function actionUpdate(string $name): Response
    {
        /** @var Rule $model */
        $model = $this->make(Rule::class, [], ['scenario' => 'update']);
        $rule = $this->findRule($name);

        $model->setAttributes([
            'previousName' => $name,
            'name' => $rule->name,
            'className' => get_class($rule),
        ]);

        if (Yii::$app->request->isPost || Yii::$app->request->isPut) {
            $requestData = Yii::$app->request->bodyParams ?: Yii::$app->request->post();

            if ($model->load($requestData, '')) {
                if ($this->make(AuthRuleEditionService::class, [$model])->run()) {
                    // For Inertia requests, redirect to index
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            // Return form with errors
            return Inertia::render('Rule/Form', [
                'rule' => [
                    'name' => $model->name ?? '',
                    'class_name' => $model->className ?? '',
                    'old_name' => $name,
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show a form with current data
        return Inertia::render('Rule/Form', [
            'rule' => [
                'name' => $model->name,
                'class_name' => $model->className,
                'old_name' => $name,
            ],
            'errors' => [],
        ]);
    }

    /**
     * Deletes an existing rule.
     *
     * @param string $name
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete(string $name): Response
    {
        $rule = $this->findRule($name);

        $this->getAuthManager()->remove($rule);
        $this->getAuthManager()->invalidateCache();

        Yii::$app->getSession()->setFlash('success', 'Authorization rule has been removed.');

        return Inertia::location('/rule');
    }

    /**
     * Find rule by name.
     *
     * @param string $name
     * @return RbacRule
     * @throws NotFoundHttpException
     */
    protected function findRule(string $name): RbacRule
    {
        $rule = $this->getAuthManager()->getRule($name);

        if (!($rule instanceof RbacRule)) {
            throw new NotFoundHttpException('Rule not found.');
        }

        return $rule;
    }
}
