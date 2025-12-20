<?php

namespace app\controllers;

use Yii;
use app\models\DisasterStatus;
use app\models\DisasterStatusSearch;
use Crenspire\Yii2Inertia\Inertia;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DisasterStatusController implements the CRUD actions for DisasterStatus model.
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
        $this->checkAccess('disasterStatus.index');
        $searchModel = new DisasterStatusSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return Inertia::render('DisasterStatus/Index', [
            'statuses' => $dataProvider->getModels(),
            'pagination' => [
                'total' => (int) $dataProvider->getPagination()->totalCount,
                'per_page' => (int) $dataProvider->getPagination()->pageSize,
                'current_page' => (int) $dataProvider->getPagination()->getPage() + 1,
                'last_page' => (int) $dataProvider->getPagination()->getPageCount(),
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
        $this->checkAccess('disasterStatus.view', $model);

        return Inertia::render('DisasterStatus/View', [
            'status' => $model,
        ]);
    }

    /**
     * Creates a new DisasterStatus model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response
     * @throws ForbiddenHttpException
     * @throws Exception|NotFoundHttpException
     */
    public function actionCreate(): Response
    {
        $this->checkAccess('disasterStatus.create');
        $model = new DisasterStatus();

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan.'));
            if (Yii::$app->request->headers->get('X-Inertia')) {
                return $this->actionView($model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return Inertia::render('DisasterStatus/Form', [
                'status' => $model,
                'errors' => $model->getErrors(),
            ]);
        }
    }

    /**
     * Updates an existing DisasterStatus model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterStatus.update', $model);

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan.'));
            if (Yii::$app->request->headers->get('X-Inertia')) {
                return $this->actionView($model->id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return Inertia::render('DisasterStatus/Form', [
                'status' => $model,
                'errors' => $model->getErrors(),
            ]);
        }
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
        $this->checkAccess('disasterStatus.delete', $model);

        if ($model->deleteWithRelated()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil dihapus.'));
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'Gagal menghapus data.'));
        }

        if (Yii::$app->request->headers->get('X-Inertia')) {
            return $this->actionIndex();
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
    * @return mixed
    */
    public function actionAddDisaster()
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
