<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\Icd10;

/**
 * Icd10Search represents the model behind the search form about `frontend\models\Icd10`.
 */
class Icd10Search extends Icd10
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['icd10', 'descriptions', 'valid', 'icd10who', 'icd10tm', 'icd10tmd'], 'safe'],
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
        $query = Icd10::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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

        $query->andFilterWhere(['like', 'icd10', $this->icd10])
            ->andFilterWhere(['like', 'descriptions', $this->descriptions])
            ->andFilterWhere(['like', 'valid', $this->valid])
            ->andFilterWhere(['like', 'icd10who', $this->icd10who])
            ->andFilterWhere(['like', 'icd10tm', $this->icd10tm])
            ->andFilterWhere(['like', 'icd10tmd', $this->icd10tmd]);

        return $dataProvider;
    }
}
