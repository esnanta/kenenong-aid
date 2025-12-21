<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\MediaFile;
use app\models\MediaFileSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use Throwable;
use yii\db\StaleObjectException;
use yii\db\Exception;

/**
 * MediaFileController implements the CRUD actions for the MediaFile model.
 */
class MediaFileController extends BaseController
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
     * Lists all MediaFile models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('mediaFile.index');
        $searchModel = new MediaFileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MediaFile model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('mediaFile.view', $model);

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new MediaFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('mediaFile.create');
        $model = new MediaFile();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing MediaFile model.
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
        $this->checkAccess('mediaFile.update', $model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MediaFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('mediaFile.delete', $model);
        $model->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the MediaFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return MediaFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): MediaFile
    {
        if (($model = MediaFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
