<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\Shelter;
use app\models\ShelterSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ShelterController implements the CRUD actions for a Shelter model.
 */
class ShelterController extends BaseController
{
    /**
     * @return array
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
     * Lists all Shelter models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('shelter.index');
        $searchModel = new ShelterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Shelter model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('shelter.view', $model);

        $providerAccessRouteShelters = new ArrayDataProvider([
            'allModels' => $model->accessRouteShelters,
        ]);
        $providerAidDistribution = new ArrayDataProvider([
            'allModels' => $model->aidDistributions,
        ]);
        $providerAidPlan = new ArrayDataProvider([
            'allModels' => $model->aidPlans,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerAccessRouteShelters' => $providerAccessRouteShelters,
            'providerAidDistribution' => $providerAidDistribution,
            'providerAidPlan' => $providerAidPlan,
        ]);
    }

    /**
     * Creates a new Shelter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('shelter');
        $model = new Shelter();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Shelter model.
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
        $this->checkAccess('shelter.update', $model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Shelter model.
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
        $this->checkAccess('shelter.delete', $model);
        $model->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Shelter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Shelter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): Shelter
    {
        if (($model = Shelter::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for AccessRouteShelters
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddAccessRouteShelters(): string
    {
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
    * for AidDistribution
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddAidDistribution(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AidDistribution');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAidDistribution', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for AidPlan
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddAidPlan(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('AidPlan');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formAidPlan', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
