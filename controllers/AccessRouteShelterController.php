<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\AccessRouteShelter;
use app\models\AccessRouteSheltersSearch;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AccessRouteSheltersController implements the CRUD actions for AccessRouteShelters model.
 */
class AccessRouteShelterController extends BaseController
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
     * Lists all AccessRouteShelters models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('accessRouteShelter-index');
        $searchModel = new AccessRouteSheltersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccessRouteShelters model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRouteShelter-view', $model);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new AccessRouteShelters model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('accessRouteShelter-create');
        $model = new AccessRouteShelter();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccessRouteShelters model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRouteShelter-update', $model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AccessRouteShelters model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRouteShelter-delete', $model);
        $model->deleteWithRelated();
        return $this->redirect(['index']);
    }

    
    /**
     * Finds the AccessRouteShelters model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return AccessRouteShelter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): AccessRouteShelter
    {
        if (($model = AccessRouteShelter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
