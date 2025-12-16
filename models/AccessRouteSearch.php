<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AccessRoute;

/**
 * app\models\AccessRouteSearch represents the model behind the search form about `app\models\AccessRoute`.
 */
 class AccessRouteSearch extends AccessRoute
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'disaster_id', 'access_route_status_id', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['route_name', 'route_geometry', 'geometry_updated_at', 'description', 'created_at', 'updated_at', 'is_deleted', 'deleted_at', 'uuid'], 'safe'],
            [['route_length_km'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AccessRoute::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'disaster_id' => $this->disaster_id,
            'route_length_km' => $this->route_length_km,
            'access_route_status_id' => $this->access_route_status_id,
            'geometry_updated_at' => $this->geometry_updated_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'verlock' => $this->verlock,
        ]);

        $query->andFilterWhere(['like', 'route_name', $this->route_name])
            ->andFilterWhere(['like', 'route_geometry', $this->route_geometry])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted])
            ->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
