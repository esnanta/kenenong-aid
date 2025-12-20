<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\Profile;
use app\models\User;
use Crenspire\Yii2Inertia\Inertia;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
     * @return Response
     */
    public function actionIndex(): Response
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

        // Join with the profile table for name search
        $query->joinWith(['profile']);

        // Apply search filter
        if (!empty($search)) {
            $query->andWhere([
                'or',
                ['like', 't_profile.name', $search],
                ['like', 'username', $search],
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

        // Get a total count before pagination
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
                'name' => $user->profile->name ?? $user->username,
                'email' => $user->email,
                'email_verified_at' => $user->confirmed_at ? date('Y-m-d H:i:s', $user->confirmed_at) : null,
                'created_at' => date('Y-m-d H:i:s', $user->created_at),
                'updated_at' => date('Y-m-d H:i:s', $user->updated_at),
            ];
        }

        return Inertia::render('User/Index', [
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
     * @return Response
     * @throws NotFoundHttpException if the user cannot be found
     */
    public function actionView(int $id): Response
    {
        $user = $this->findModel($id);

        return Inertia::render('User/View', [
            'user' => [
                'id' => $user->id,
                'name' => $user->profile->name ?? $user->username,
                'email' => $user->email,
                'email_verified_at' => $user->confirmed_at ? date('Y-m-d H:i:s', $user->confirmed_at) : null,
                'created_at' => date('Y-m-d H:i:s', $user->created_at),
                'updated_at' => date('Y-m-d H:i:s', $user->updated_at),
            ],
        ]);
    }

    /**
     * Creates a new user.
     *
     * @return Response
     * @throws Exception
     */
    public function actionCreate(): Response
    {
        $model = new User();
        $model->scenario = 'create';

        if (Yii::$app->request->isPost) {
            $requestData = Yii::$app->request->post();

            if ($model->load($requestData, '')) {
                // Set required fields for yii2-usuario
                $model->username = $requestData['email'] ?? '';
                $model->email = $requestData['email'] ?? '';

                if ($model->validate() && $model->save()) {
                    // Create a profile record with the name
                    if (!empty($requestData['name'])) {
                        $profile = new Profile();
                        $profile->user_id = $model->id;
                        $profile->name = $requestData['name'];
                        $profile->save();
                    }

                    // For Inertia requests, directly render the index page
                    // This allows onSuccess callback to fire properly
                    if (Yii::$app->request->headers->get('X-Inertia')) {
                        return $this->actionIndex();
                    }
                    return $this->redirect(['index']);
                }
            }
            
            // If we get here, validation failed - return a form with errors
            // Return 200 status but include errors in props (similar to a Login form)
            // Inertia will handle this and show errors inline
            return Inertia::render('Users/Form', [
                'user' => [
                    'name' => $requestData['name'] ?? '',
                    'email' => $model->email ?? '',
                    'password' => '',
                ],
                'errors' => $model->errors,
            ]);
        }

        // GET request - show an empty form
        return Inertia::render('User/Form', [
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
     * @return Response
     * @throws NotFoundHttpException if the user cannot be found
     * @throws Exception
     */
    public function actionUpdate(int $id): Response
    {
        $model = $this->findModel($id);
        $profile = $model->profile;

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
            $userLoaded = $model->load($requestData, '');
            $profileLoaded = $profile && $profile->load($requestData, '');

            if ($userLoaded || $profileLoaded) {
                // If the password is empty, don't validate or save it
                if (empty($requestData['password'])) {
                    // Only validate and save name (in profile) and email (in user)
                    // Reset a scenario to default before validating specific attributes
                    $model->scenario = Model::SCENARIO_DEFAULT;
                    $userValid = $model->validate(['email']);
                    $profileValid = !$profile || $profile->validate(['name']);

                    if ($userValid && $profileValid) {
                        $model->save(false, ['email']);
                        if ($profile) {
                            $profile->save(false, ['name']);
                        }
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
                    $model->scenario = Model::SCENARIO_DEFAULT;
                    $userValid = $model->validate();
                    $profileValid = !$profile || $profile->validate(['name']);

                    if ($userValid && $profileValid && $model->save()) {
                        if ($profile) {
                            $profile->save(false, ['name']);
                        }
                        // For Inertia requests, directly render the index page
                        // This allows onSuccess callback to fire properly
                        if (Yii::$app->request->headers->get('X-Inertia')) {
                            return $this->actionIndex();
                        }
                        return $this->redirect(['index']);
                    }
                }
            }
            
            // If we get here, validation failed - return a form with errors
            // Return 200 status but include errors in props (similar to a Login form)
            // Inertia will handle this and show errors inline
            return Inertia::render('User/Form', [
                'user' => [
                    'id' => $model->id,
                    'name' => $profile->name ?? $model->username,
                    'email' => $model->email ?? '',
                    'password' => '',
                ],
                'errors' => array_merge($model->errors, $profile ? $profile->errors : []),
            ]);
        }

        // GET request - show a form with current data
        return Inertia::render('User/Form', [
            'user' => [
                'id' => $model->id,
                'name' => $profile->name ?? $model->username,
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
     * @return Response
     * @throws NotFoundHttpException if the user cannot be found
     * @throws Exception
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);
        
        // Prevent deleting yourself
        if ($model->id === Yii::$app->user->id) {
            Yii::$app->session->setFlash('error', 'You cannot delete your own account.');
            return $this->redirect(['index']);
        }

        // Softly delete
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
    protected function findModel(int $id): User
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested user does not exist.');
    }
}
