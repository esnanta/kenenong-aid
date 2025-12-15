<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use Crenspire\Yii2Inertia\Inertia;
use app\controllers\BaseController;
use app\models\User;

class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all users.
     *
     * @return \yii\web\Response
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $search = $request->get('search', '');
        $page = (int)$request->get('page', 1);
        $perPage = (int)$request->get('per_page', 20);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $emailVerified = $request->get('email_verified', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $query = User::find();

        // Apply search filter
        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 'name', $search],
                ['like', 'email', $search],
            ]);
        }

        // Apply email verification filter
        if ($emailVerified !== '') {
            if ($emailVerified === 'verified') {
                $query->andWhere(['is not', 'email_verified_at', null]);
            } elseif ($emailVerified === 'unverified') {
                $query->andWhere(['email_verified_at' => null]);
            }
        }

        // Apply date range filter
        if (!empty($dateFrom)) {
            $query->andWhere(['>=', 'created_at', $dateFrom]);
        }
        if (!empty($dateTo)) {
            $query->andWhere(['<=', 'created_at', $dateTo . ' 23:59:59']);
        }

        // Get total count before pagination
        $total = $query->count();

        // Apply sorting
        $allowedSortColumns = ['id', 'name', 'email', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
        $sortOrder = strtolower($sortOrder) === 'asc' ? SORT_ASC : SORT_DESC;
        $query->orderBy([$sortBy => $sortOrder]);

        // Apply pagination
        $offset = ($page - 1) * $perPage;
        $users = $query->offset($offset)
            ->limit($perPage)
            ->all();

        // Format users for frontend
        $usersData = [];
        foreach ($users as $user) {
            $usersData[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ];
        }

        return Inertia::render('Users/Index', [
            'users' => $usersData,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => ceil($total / $perPage),
            ],
            'filters' => [
                'search' => $search,
                'email_verified' => $emailVerified,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
            'sort' => [
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
            ],
        ]);
    }

    /**
     * Displays a single user.
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the user cannot be found
     */
    public function actionView($id)
    {
        $user = $this->findModel($id);

        return Inertia::render('Users/View', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
        ]);
    }

    /**
     * Creates a new user.
     *
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post(), '')) {
                if ($model->validate() && $model->save()) {
                    // For Inertia requests, directly render the index page
                    // This allows onSuccess callback to fire properly
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }
            
            // If we get here, validation failed - return form with errors
            // Return 200 status but include errors in props (similar to Login form)
            // Inertia will handle this and show errors inline
            return Inertia::render('Users/Form', [
                'user' => [
                    'name' => $model->name ?? '',
                    'email' => $model->email ?? '',
                    'password' => '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show empty form
        return Inertia::render('Users/Form', [
            'user' => [
                'name' => '',
                'email' => '',
                'password' => '',
            ],
            'errors' => [],
        ]);
    }

    /**
     * Updates an existing user.
     *
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the user cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // Handle both POST and PUT requests
        $isPost = Yii::$app->request->isPost;
        $isPut = Yii::$app->request->isPut;
        
        if ($isPost || $isPut) {
            // For PUT requests, get data from bodyParams; for POST, use post()
            // If bodyParams is empty for PUT, try parsing rawBody
            if ($isPut) {
                $requestData = Yii::$app->request->bodyParams;
                // If bodyParams is empty, parse JSON from rawBody
                if (empty($requestData) && Yii::$app->request->contentType === 'application/json') {
                    $rawBody = Yii::$app->request->rawBody;
                    if (!empty($rawBody)) {
                        $requestData = json_decode($rawBody, true) ?: [];
                    }
                }
            } else {
                $requestData = Yii::$app->request->post();
            }
            
            // Load the data
            if ($model->load($requestData, '')) {
                // If password is empty, don't validate or save it
                if (empty($requestData['password'])) {
                    // Only validate and save name and email
                    // Reset scenario to default before validating specific attributes
                    $model->scenario = \yii\base\Model::SCENARIO_DEFAULT;
                    if ($model->validate(['name', 'email'])) {
                        $model->save(false, ['name', 'email']);
                        // For Inertia requests, directly render the index page
                        // This allows onSuccess callback to fire properly
                        if (Yii::$app->request->headers->get('X-Inertia')) {
                            return $this->actionIndex();
                        }
                        return $this->redirect(['index']);
                    }
                } else {
                    // Password is provided, validate everything
                    // Use default scenario - password validation rule applies to all scenarios
                    $model->scenario = \yii\base\Model::SCENARIO_DEFAULT;
                    if ($model->validate() && $model->save()) {
                        // For Inertia requests, directly render the index page
                        // This allows onSuccess callback to fire properly
                        if (Yii::$app->request->headers->get('X-Inertia')) {
                            return $this->actionIndex();
                        }
                        return $this->redirect(['index']);
                    }
                }
            }
            
            // If we get here, validation failed - return form with errors
            // Return 200 status but include errors in props (similar to Login form)
            // Inertia will handle this and show errors inline
            return Inertia::render('Users/Form', [
                'user' => [
                    'id' => $model->id,
                    'name' => $model->name ?? '',
                    'email' => $model->email ?? '',
                    'password' => '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show form with current data
        return Inertia::render('Users/Form', [
            'user' => [
                'id' => $model->id,
                'name' => $model->name,
                'email' => $model->email,
                'password' => '',
            ],
            'errors' => [],
        ]);
    }

    /**
     * Deletes an existing user (soft delete).
     *
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the user cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        // Prevent deleting yourself
        if ($model->id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'You cannot delete your own account.');
            return $this->redirect(['index']);
        }

        // Soft delete
        $model->trash();

        return Inertia::location('/users');
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested user does not exist.');
    }
}
