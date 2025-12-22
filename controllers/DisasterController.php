<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\Disaster;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DisasterController implements the CRUD actions for a Disaster model.
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
        $this->checkAccess('disaster-index');
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
                'disaster_type_id' => $disaster->disaster_type_id,
                'disaster_type_label' => $disaster->getDisasterTypeLabel(),
                'disaster_status_id' => $disaster->disaster_status_id,
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
        $this->checkAccess('disaster-view', $model);
        
        return Inertia::render('Disaster/View', [
            'disaster' => [
                'id' => $model->id,
                'disaster_type_id' => $model->disaster_type_id,
                'disaster_type_label' => $model->getDisasterTypeLabel(),
                'disaster_status_id' => $model->disaster_status_id,
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
     * @return Response
     * @throws ForbiddenHttpException|Exception
     */
    public function actionCreate(): Response
    {
        $this->checkAccess('disaster-create');
        $model = new Disaster();

        if (Yii::$app->request->isPost) {
            return $this->processDisasterFormSubmission($model);
        }

        // GET request - show an empty form
        return $this->renderDisasterForm();
    }

    /**
     * Updates an existing Disaster model.
     * If the update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException|Exception
     */
    public function actionUpdate(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disaster-update', $model);
        
        $isPost = Yii::$app->request->isPost;
        $isPut = Yii::$app->request->isPut;

        if ($isPost || $isPut) {
            return $this->processDisasterFormSubmission($model);
        }

        // GET request - show a form with current data
        return $this->renderDisasterForm($model);
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
        $this->checkAccess('disaster-delete', $model);
        
        // Softly delete
        $model->is_deleted = 1;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->deleted_by = Yii::$app->user->id;

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', 'Disaster deleted successfully');
        } else {
            Yii::$app->session->setFlash('error', 'Failed to delete disaster');
        }

        if (Yii::$app->request->headers->get('X-Inertia')) {
            return $this->actionIndex();
        }

        return $this->redirect(['/disasters']);
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

    /**
     * Renders the Disaster form.
     * @param Disaster|null $model The Disaster model instance, or null for a new form.
     * @param array $errors An array of validation errors.
     * @return Response
     */
    private function renderDisasterForm(?Disaster $model = null, array $errors = []): Response
    {
        $disasterData = null;
        if ($model) {
            $disasterData = [
                'id' => $model->id,
                'disaster_type_id' => $model->disaster_type_id,
                'disaster_status_id' => $model->disaster_status_id,
                'start_date' => $model->start_date,
                'end_date' => $model->end_date,
                'description' => $model->description,
            ];
        }

        return Inertia::render('Disaster/Form', [
            'disaster' => $disasterData,
            'errors' => $errors,
            'disasterTypes' => Disaster::getDisasterTypes(),
            'disasterStatuses' => Disaster::getDisasterStatuses(),
        ]);
    }

    /**
     * Processes form submission for Disaster models (create/update).
     * Loads request data, validates, and saves the model.
     * Returns a redirect response on success or renders the form with errors on failure.
     * @param Disaster $model The Disaster model instance to process.
     * @return Response
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    private function processDisasterFormSubmission(Disaster $model): Response
    {
        $request = Yii::$app->request;
        $requestData = $request->isPut ? $request->bodyParams : $request->post();

        if ($request->isPut && empty($requestData) && $request->contentType === 'application/json') {
            $rawBody = $request->rawBody;
            if (!empty($rawBody)) {
                $requestData = json_decode($rawBody, true) ?: [];
            }
        }

        if ($model->load($requestData, '')) {
            if ($model->validate() && $model->save()) {
                if ($request->headers->get('X-Inertia')) {
                    return $this->actionIndex();
                }
                return $this->redirect(['/disasters']);
            }
        }

        // If we get here, validation failed or the model couldn't load
        return $this->renderDisasterForm($model, $model->errors);
    }
}
