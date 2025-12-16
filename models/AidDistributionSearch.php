<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AidDistribution;

/**
 * app\models\AidDistributionSearch represents the model behind the search form about `app\models\AidDistribution`.
 */
 class AidDistributionSearch extends AidDistribution
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'aid_plan_id', 'shelter_id', 'distributed_by', 'created_by', 'updated_by', 'deleted_by', 'verlock'], 'integer'],
            [['distribution_date', 'notes', 'created_at', 'updated_at', 'is_deleted', 'deleted_at', 'uuid'], 'safe'],
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
        $query = AidDistribution::find();

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
            'aid_plan_id' => $this->aid_plan_id,
            'shelter_id' => $this->shelter_id,
            'distribution_date' => $this->distribution_date,
            'distributed_by' => $this->distributed_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
            'deleted_by' => $this->deleted_by,
            'verlock' => $this->verlock,
        ]);

        $query->andFilterWhere(['like', 'notes', $this->notes])
            ->andFilterWhere(['like', 'is_deleted', $this->is_deleted])
            ->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
