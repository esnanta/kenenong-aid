<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\EntityType;
use app\models\EntityTypeSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * EntityTypeController implements the CRUD actions for EntityType model.
 */
class EntityTypeController extends BaseController
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
     * Lists all EntityType models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('entityType.index');
        $searchModel = new EntityTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EntityType model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('entityType.view', $model);

        $providerMediaFiles = new ArrayDataProvider([
            'allModels' => $model->mediaFiles,
        ]);
        $providerVerification = new ArrayDataProvider([
            'allModels' => $model->verifications,
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'providerMediaFiles' => $providerMediaFiles,
            'providerVerification' => $providerVerification,
        ]);
    }

    /**
     * Creates a new EntityType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('entityType.create');
        $model = new EntityType();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing EntityType model.
     * If the update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return Response|string
     * @throws NotFoundHttpException
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('entityType.update', $model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing EntityType model.
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
        $this->checkAccess('entityType.delete', $model);
        $model->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the EntityType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return EntityType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): EntityType
    {
        if (($model = EntityType::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for MediaFiles
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddMediaFiles(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('MediaFiles');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formMediaFiles', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Verification
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddVerification(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Verification');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formVerification', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}