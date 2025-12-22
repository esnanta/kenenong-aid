<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\DisasterStatus;
use app\models\DisasterStatusSearch;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DisasterStatusController implements the CRUD actions for the DisasterStatus model.
 */
class DisasterStatusController extends BaseController
{
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
     * Lists all DisasterStatus models.
     * @return Response
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): Response
    {
        $this->checkAccess('disasterStatus-index');
        $searchModel = new DisasterStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $statuses = $dataProvider->getModels();

        $statusesData = array_map(function ($status) {
            return [
                'id' => $status->id,
                'code' => $status->code,
                'title' => $status->title,
                'description' => $status->description,
            ];
        }, $statuses);

        return Inertia::render('DisasterStatus/Index', [
            'statuses' => $statusesData,
            'pagination' => [
                'total' => $dataProvider->getPagination()->totalCount,
                'per_page' => $dataProvider->getPagination()->pageSize,
                'current_page' => $dataProvider->getPagination()->getPage() + 1,
                'last_page' => $dataProvider->getPagination()->getPageCount(),
            ],
            'filters' => Yii::$app->request->queryParams,
            'sort' => [
                'sort_by' => Yii::$app->request->get('sort_by', 'id'),
                'sort_order' => Yii::$app->request->get('sort_order', 'asc'),
            ],
        ]);
    }

    /**
     * Displays a single DisasterStatus model.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterStatus-view', $model);

        return Inertia::render('DisasterStatus/View', [
            'status' => $model,
        ]);
    }

    /**
     * Creates a new DisasterStatus model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return Response
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionCreate(): Response
    {
        $this->checkAccess('disasterStatus-create');
        $model = new DisasterStatus();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), '')) {
                if ($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data saved successfully.'));
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return Inertia::location(Url::to(['index']));
                    }
                    return $this->redirect(['index']);
                }
            }

            return Inertia::render('DisasterStatus/Form', [
                'status' => null,
                'errors' => $model->errors,
            ]);
        }

        return Inertia::render('DisasterStatus/Form', [
            'status' => null,
            'errors' => [],
        ]);
    }

    /**
     * Updates an existing DisasterStatus model.
     * If the update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterStatus-update', $model);

        $isPost = Yii::$app->request->isPost;
        $isPut = Yii::$app->request->isPut;

        if ($isPost || $isPut) {
            if ($isPut) {
                $requestData = Yii::$app->request->bodyParams;
                if (empty($requestData) && Yii::$app->request->contentType === 'application/json') {
                    $rawBody = Yii::$app->request->rawBody;
                    if (!empty($rawBody)) {
                        $requestData = json_decode($rawBody, true) ?: [];
                    }
                }
            } else {
                $requestData = Yii::$app->request->post();
            }

            if ($model->load($requestData, '')) {
                if ($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data saved successfully.'));
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return Inertia::location(Url::to(['index']));
                    }
                    return $this->redirect(['index']);
                }
            }

            return Inertia::render('DisasterStatus/Form', [
                'status' => $model,
                'errors' => $model->errors,
            ]);
        }

        return Inertia::render('DisasterStatus/Form', [
            'status' => $model,
            'errors' => [],
        ]);
    }

    /**
     * Deletes an existing DisasterStatus model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterStatus-delete', $model);

        // Soft delete manually
        $model->is_deleted = 1;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->deleted_by = Yii::$app->user->id;

        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Data deleted successfully.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Failed to delete data.'));
        }

        if (Yii::$app->request->headers->get('X-Inertia')) {
            return Inertia::location(Url::to(['index']));
        }

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the DisasterStatus model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisasterStatus the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): DisasterStatus
    {
        if (($model = DisasterStatus::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Disaster
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddDisaster(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Disaster');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formDisaster', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
