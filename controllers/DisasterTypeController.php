<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\DisasterType;
use app\models\DisasterTypeSearch;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\db\Exception;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DisasterTypeController implements the CRUD actions for DisasterType model.
 */
class DisasterTypeController extends BaseController
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
     * Lists all DisasterType models.
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     */
    public function actionIndex(): \yii\web\Response
    {
        $this->checkAccess('disasterType.index');
        $searchModel = new DisasterTypeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return Inertia::render('DisasterType/Index', [
            'types' => $dataProvider->getModels(),
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
     * Displays a single DisasterType model.
     * @param integer $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): \yii\web\Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterType.view', $model);
        
        return Inertia::render('DisasterType/View', [
            'type' => $model,
        ]);
    }

    /**
     * Creates a new DisasterType model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionCreate()
    {
        $this->checkAccess('disasterType.create');
        $model = new DisasterType();

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), '')) {
                if ($model->validate() && $model->save()) {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan.'));
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            return Inertia::render('DisasterType/Form', [
                'type' => null,
                'errors' => $model->errors,
            ]);
        }

        return Inertia::render('DisasterType/Form', [
            'type' => null,
            'errors' => [],
        ]);
    }

    /**
     * Updates an existing DisasterType model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     * @throws Exception
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterType.update', $model);

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
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Data berhasil disimpan.'));
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }

            return Inertia::render('DisasterType/Form', [
                'type' => $model,
                'errors' => $model->errors,
            ]);
        }

        return Inertia::render('DisasterType/Form', [
            'type' => $model,
            'errors' => [],
        ]);
    }

    /**
     * Deletes an existing DisasterType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws Exception
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        $this->checkAccess('disasterType.delete', $model);

        // Soft delete manually
        $model->is_deleted = 1;
        $model->deleted_at = date('Y-m-d H:i:s');
        $model->deleted_by = Yii::$app->user->id;

        if ($model->save(false)) {
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
     * Finds the DisasterType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DisasterType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel(int $id): DisasterType
    {
        if (($model = DisasterType::findOne($id)) !== null) {
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
