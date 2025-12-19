<?php

namespace app\controllers;

use Yii;
use app\models\AccessRoute;
use app\models\AccessRouteSearch;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * AccessRouteController implements the CRUD actions for AccessRoute model.
 */
class AccessRouteController extends BaseController
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
     * Lists all AccessRoute models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex()
    {
        $this->checkAccess('accessRoute.index');
        $searchModel = new AccessRouteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccessRoute model.
     * @param integer $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRoute.view', $model);

        $providerAccessRouteShelters = new ArrayDataProvider([
            'allModels' => $model->accessRouteShelters,
        ]);
        $providerAccessRouteVehicles = new ArrayDataProvider([
            'allModels' => $model->accessRouteVehicles,
        ]);
        return $this->render('view', [
            'model' => $model,
            'providerAccessRouteShelters' => $providerAccessRouteShelters,
            'providerAccessRouteVehicles' => $providerAccessRouteVehicles,
        ]);
    }

    /**
     * Creates a new AccessRoute model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     * @throws ForbiddenHttpException|Exception
     */
    public function actionCreate()
    {

        $this->checkAccess('accessRoute.create');
        $model = new AccessRoute();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AccessRoute model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return Response|string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException|Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRoute.update', $model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AccessRoute model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException|Exception
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('accessRoute.delete', $model);
        $model->deleteWithRelated();
        return $this->redirect(['index']);
    }

    
    /**
     * Finds the AccessRoute model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccessRoute the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): AccessRoute
    {
        if (($model = AccessRoute::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AccessRouteShelters
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     */
    public function actionAddAccessRouteShelters(): string
    {
        $this->checkAccess('accessRoute.update');

        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AccessRouteShelters');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAccessRouteShelters', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }

    /**
     * Action to load a tabular form grid
     * for AccessRouteVehicles
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @author Yohanes Candrajaya <moo.tensai@gmail.com>
     * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
     *
     */
    public function actionAddAccessRouteVehicles(): string
    {
        $this->checkAccess('accessRoute.update');

        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AccessRouteVehicles');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAccessRouteVehicles', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
