<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AidPlanDetails;

/**
 * app\models\AidPlanDetailSearch represents the model behind the search form about `app\models\AidPlanDetails`.
 */
 class AidPlanDetailSearch extends AidPlanDetails
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'aid_plan_id', 'item_id', 'quantity', 'unit_id', 'verlock'], 'integer'],
            [['uuid'], 'safe'],
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
        $query = AidPlanDetails::find();

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
            'item_id' => $this->item_id,
            'quantity' => $this->quantity,
            'unit_id' => $this->unit_id,
            'verlock' => $this->verlock,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid]);

        return $dataProvider;
    }
}
