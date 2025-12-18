<?php

namespace app\controllers;

use Yii;
use Da\User\Model\Rule;
use Da\User\Search\RuleSearch;
use Da\User\Service\AuthRuleEditionService;
use Da\User\Validator\AjaxRequestModelValidator;
use Da\User\Filter\AccessRuleFilter;
use Da\User\Traits\AuthManagerAwareTrait;
use Da\User\Traits\ContainerAwareTrait;
use Crenspire\Yii2Inertia\Inertia;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\controllers\BaseController;

class RuleController extends BaseController
{
    use AuthManagerAwareTrait;
    use ContainerAwareTrait;

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'ruleConfig' => [
                    'class' => AccessRuleFilter::class,
                ],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all rules.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
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
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($name)
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
     * @return \yii\web\Response
     */
    public function actionCreate()
    {
        $model = $this->make(Rule::class, [], ['scenario' => 'create', 'className' => \yii\rbac\Rule::class]);

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

        // GET request - show empty form
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
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($name)
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

        // GET request - show form with current data
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
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($name)
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
     * @return \yii\rbac\Rule
     * @throws NotFoundHttpException
     */
    protected function findRule($name)
    {
        $rule = $this->getAuthManager()->getRule($name);

        if (!($rule instanceof \yii\rbac\Rule)) {
            throw new NotFoundHttpException('Rule not found.');
        }

        return $rule;
    }
}

