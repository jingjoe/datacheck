<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Deathcup;

/**
 * DeathcupSearch represents the model behind the search form about `frontend\models\Deathcup`.
 */
class DeathcupSearch extends Deathcup
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['hospcode', 'hosname', 'cid', 'fname', 'lname', 'sex', 'age', 'nation', 'death_y', 'death_m', 'death_d', 'cause_death', 'death_place', 'address_death', 'inform_y', 'inform_m', 'inform_d', 'current_address', 'report_prov', 'report_y', 'report_m'], 'safe'],
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
        $query = Deathcup::find();

        // add conditions that should always apply here


        $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'pagination'=>[
            'pageSize'=>10 //แบ่งหน้า
        ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'hospcode', $this->hospcode])
            ->andFilterWhere(['like', 'hosname', $this->hosname])
            ->andFilterWhere(['like', 'cid', $this->cid])
            ->andFilterWhere(['like', 'fname', $this->fname])
            ->andFilterWhere(['like', 'lname', $this->lname])
            ->andFilterWhere(['like', 'sex', $this->sex])
            ->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'nation', $this->nation])
            ->andFilterWhere(['like', 'death_y', $this->death_y])
            ->andFilterWhere(['like', 'death_m', $this->death_m])
            ->andFilterWhere(['like', 'death_d', $this->death_d])
            ->andFilterWhere(['like', 'cause_death', $this->cause_death])
            ->andFilterWhere(['like', 'death_place', $this->death_place])
            ->andFilterWhere(['like', 'address_death', $this->address_death])
            ->andFilterWhere(['like', 'inform_y', $this->inform_y])
            ->andFilterWhere(['like', 'inform_m', $this->inform_m])
            ->andFilterWhere(['like', 'inform_d', $this->inform_d])
            ->andFilterWhere(['like', 'current_address', $this->current_address])
            ->andFilterWhere(['like', 'report_prov', $this->report_prov])
            ->andFilterWhere(['like', 'report_y', $this->report_y])
            ->andFilterWhere(['like', 'report_m', $this->report_m]);

        return $dataProvider;
    }
}
