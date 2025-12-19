<?php

namespace app\controllers;

use Yii;
use app\models\Disaster;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use Crenspire\Yii2Inertia\Inertia;
use yii\web\Response;

/**
 * DisasterController implements the CRUD actions for Disaster model.
 */
class DisasterController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Disaster models.
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): Response
    {
        $this->checkAccess('disaster.index');
        $request = Yii::$app->request;

        // Get filter parameters
        $search = $request->get('search');
        $disasterTypeId = $request->get('disaster_type_id');
        $disasterStatusId = $request->get('disaster_status_id');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        // Get sort parameters
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Get pagination
        $page = (int)$request->get('page', 1);
        $perPage = 10;

        // Build query
        $query = Disaster::find()->where(['is_deleted' => 0]);

        // Apply filters
        if ($search) {
            $query->andWhere(['like', 'description', $search]);
        }

        if ($disasterTypeId !== null && $disasterTypeId !== '') {
            $query->andWhere(['disaster_type_id' => (int)$disasterTypeId]);
        }

        if ($disasterStatusId !== null && $disasterStatusId !== '') {
            $query->andWhere(['disaster_status_id' => (int)$disasterStatusId]);
        }

        if ($dateFrom) {
            $query->andWhere(['>=', 'start_date', $dateFrom]);
        }

        if ($dateTo) {
            $query->andWhere(['<=', 'start_date', $dateTo]);
        }

        // Apply sorting
        $query->orderBy([$sortBy => $sortOrder === 'asc' ? SORT_ASC : SORT_DESC]);

        // Get total count
        $totalCount = $query->count();

        // Apply pagination
        $query->offset(($page - 1) * $perPage)->limit($perPage);

        // Get disasters
        $disasters = $query->all();

        // Format disasters data
        $disastersData = array_map(function ($disaster) {
            return [
                'id' => $disaster->id,
                'disaster_type_id' => (int)$disaster->disaster_type_id,
                'disaster_type_label' => $disaster->getDisasterTypeLabel(),
                'disaster_status_id' => (int)$disaster->disaster_status_id,
                'disaster_status_label' => $disaster->getDisasterStatusLabel(),
                'start_date' => $disaster->start_date,
                'end_date' => $disaster->end_date,
                'description' => $disaster->description,
                'created_at' => $disaster->created_at,
                'updated_at' => $disaster->updated_at,
            ];
        }, $disasters);

        return Inertia::render('Disaster/Index', [
            'disasters' => $disastersData,
            'pagination' => [
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $page,
                'last_page' => ceil($totalCount / $perPage),
            ],
            'filters' => [
                'search' => $search,
                'disaster_type_id' => $disasterTypeId !== null && $disasterTypeId !== '' ? (int)$disasterTypeId : null,
                'disaster_status_id' => $disasterStatusId !== null && $disasterStatusId !== '' ? (int)$disasterStatusId : null,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'sort' => [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
            'disasterTypes' => Disaster::getDisasterTypes(),
            'disasterStatuses' => Disaster::getDisasterStatuses(),
        ]);
    }

    /**
     * Displays a single Disaster model.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disaster.view', $model);
        
        return Inertia::render('Disaster/View', [
            'disaster' => [
                'id' => $model->id,
                'disaster_type_id' => (int)$model->disaster_type_id,
                'disaster_type_label' => $model->getDisasterTypeLabel(),
                'disaster_status_id' => (int)$model->disaster_status_id,
                'disaster_status_label' => $model->getDisasterStatusLabel(),
                'start_date' => $model->start_date,
                'end_date' => $model->end_date,
                'description' => $model->description,
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ]);
    }

    /**
     * Creates a new Disaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws ForbiddenHttpException|Exception
     */
    public function actionCreate()
    {
        $this->checkAccess('disaster.create');
        $model = new Disaster();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), '')) {
                if ($model->validate() && $model->save()) {
                    // For Inertia requests, directly render the index page
                    // This allows onSuccess callback to fire properly
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['/disasters']);
                }
            }

            // If we get here, validation failed - return form with errors
            return Inertia::render('Disaster/Form', [
                'disaster' => null,
                'errors' => $model->errors,
                'disasterTypes' => Disaster::getDisasterTypes(),
                'disasterStatuses' => Disaster::getDisasterStatuses(),
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Disaster/Form', [
            'disaster' => null,
            'errors' => [],
            'disasterTypes' => Disaster::getDisasterTypes(),
            'disasterStatuses' => Disaster::getDisasterStatuses(),
        ]);
    }

    /**
     * Updates an existing Disaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException|Exception
     */
    public function actionUpdate(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disaster.update', $model);
        
        // Handle both POST and PUT requests
        $isPost = Yii::$app->request->isPost;
        $isPut = Yii::$app->request->isPut;

        if ($isPost || $isPut) {
            // For PUT requests, get data from bodyParams; for POST, use post()
            if ($isPut) {
                $requestData = Yii::$app->request->bodyParams;
                // If bodyParams is empty, parse JSON from rawBody
                if (empty($requestData) && Yii::$app->request->contentType === 'application/json') {
                    $rawBody = Yii::$app->request->rawBody;
                    if (!empty($rawBody)) {
                        $requestData = json_decode($rawBody, true) ?: [];
                    }
                }
            } else {
                $requestData = Yii::$app->request->post();
            }

            // Load the data
            if ($model->load($requestData, '')) {
                if ($model->validate() && $model->save()) {
                    // For Inertia requests, directly render the index page
                    // This allows onSuccess callback to fire properly
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['/disasters']);
                }
            }

            // If we get here, validation failed - return form with errors
            return Inertia::render('Disaster/Form', [
                'disaster' => [
                    'id' => $model->id,
                    'disaster_type_id' => (int)$model->disaster_type_id,
                    'disaster_status_id' => (int)$model->disaster_status_id,
                    'start_date' => $model->start_date,
                    'end_date' => $model->end_date,
                    'description' => $model->description,
                ],
                'errors' => $model->errors,
                'disasterTypes' => Disaster::getDisasterTypes(),
                'disasterStatuses' => Disaster::getDisasterStatuses(),
            ]);
        }

        // GET request - show form with current data
        return Inertia::render('Disaster/Form', [
            'disaster' => [
                'id' => $model->id,
                'disaster_type_id' => (int)$model->disaster_type_id,
                'disaster_status_id' => (int)$model->disaster_status_id,
                'start_date' => $model->start_date,
                'end_date' => $model->end_date,
                'description' => $model->description,
            ],
            'errors' => [],
            'disasterTypes' => Disaster::getDisasterTypes(),
            'disasterStatuses' => Disaster::getDisasterStatuses(),
        ]);
    }

    /**
     * Deletes an existing Disaster model (soft delete).
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException|Exception
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disaster.delete', $model);
        
        // Soft delete
        $model->is_deleted = 1;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->deleted_by = Yii::$app->user->id;

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Disaster deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete disaster');
        }

        return Inertia::location('/disasters');
    }

    /**
     * Finds the Disaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Disaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Disaster
    {
        if (($model = Disaster::findOne(['id' => $id, 'is_deleted' => 0])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
