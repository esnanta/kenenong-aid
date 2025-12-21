<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\Unit;
use app\models\UnitSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\db\Exception;

/**
 * UnitController implements the CRUD actions for a Unit model.
 */
class UnitController extends BaseController
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
     * Lists all Unit models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('unit.index');
        $searchModel = new UnitSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Unit model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('unit.view', $model);

        $providerAidDistributionDetails = new ArrayDataProvider([
            'allModels' => $model->aidDistributionDetails,
        ]);
        $providerAidPlanDetails = new ArrayDataProvider([
            'allModels' => $model->aidPlanDetails,
        ]);
        return $this->render('view', [
            'model' => $model,
            'providerAidDistributionDetails' => $providerAidDistributionDetails,
            'providerAidPlanDetails' => $providerAidPlanDetails,
        ]);
    }

    /**
     * Creates a new Unit model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('unit.create');
        $model = new Unit();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Unit model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('unit.update',$model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Unit model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('unit.delete', $model);
        $model->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Unit model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Unit the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Unit
    {
        if (($model = Unit::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for AidDistributionDetails
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddAidDistributionDetails(): string
    {
        return $this->handleAjaxTabularForm('AidDistributionDetails', '_formAidDistributionDetails');
    }
    
    /**
    * Action to load a tabular form grid
    * for AidPlanDetails
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddAidPlanDetails(): string
    {
        return $this->handleAjaxTabularForm('AidPlanDetails', '_formAidPlanDetails');
    }

    /**
     * Handles the common logic for loading tabular form grids via AJAX.
     *
     * @param string $postKey The key used to retrieve data from the POST request.
     * @param string $viewFile The view file to render for the tabular form.
     * @return string
     * @throws NotFoundHttpException if the request is not AJAX.
     */
    private function handleAjaxTabularForm(string $postKey, string $viewFile): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post($postKey);
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax($viewFile, ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
