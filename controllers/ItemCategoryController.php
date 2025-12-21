<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\ItemCategory;
use app\models\ItemCategorySearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\db\Exception;

/**
 * ItemCategoryController implements the CRUD actions for the ItemCategory model.
 */
class ItemCategoryController extends BaseController
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
     * Lists all ItemCategory models.
     * @return string
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): string
    {
        $this->checkAccess('itemCategory.index');
        $searchModel = new ItemCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItemCategory model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $model = $this->findModel($id);
        $this->checkAccess('itemCategory.view', $model);

        $providerItem = new ArrayDataProvider([
            'allModels' => $model->items,
        ]);
        return $this->render('view', [
            'model' => $model, // Use the already fetched model
            'providerItem' => $providerItem,
        ]);
    }

    /**
     * Creates a new ItemCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return Response|string
     * @throws Exception
     * @throws ForbiddenHttpException
     */
    public function actionCreate()
    {
        $this->checkAccess('itemCategory.create');
        $model = new ItemCategory();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ItemCategory model.
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
        $this->checkAccess('itemCategory.update',$model);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ItemCategory model.
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
        $this->checkAccess('itemCategory.delete', $model);
        $model->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the ItemCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return ItemCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): ItemCategory
    {
        if (($model = ItemCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
    
    /**
    * Action to load a tabular form grid
    * for Item
    * @author Yohanes Candrajaya <moo.tensai@gmail.com>
    * @author Jiwantoro Ndaru <jiwanndaru@gmail.com>
    *
    * @return string
    * @throws NotFoundHttpException
    */
    public function actionAddItem(): string
    {
        if (Yii::$app->request->isAjax) {
            $row = Yii::$app->request->post('Item');
            if (!empty($row)) {
                $row = array_values($row);
            }
            if((Yii::$app->request->post('isNewRecord') && Yii::$app->request->post('_action') == 'load' && empty($row)) || Yii::$app->request->post('_action') == 'add')
                $row[] = [];
            return $this->renderAjax('_formItem', ['row' => $row]);
        } else {
            throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
        }
    }
}
