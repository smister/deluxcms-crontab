<?php

namespace deluxcms\crontab\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use deluxcms\crontab\models\Crontabs;

/**
 * CrontabsSearch represents the model behind the search form about `\deluxcms\crontab\models\Crontabs`.
 */
class CrontabsSearch extends Crontabs
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description', 'min', 'hour', 'day', 'month', 'week', 'command'], 'safe'],
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
        $query = Crontabs::find();

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
            'type' => $this->type,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'min', $this->min])
            ->andFilterWhere(['like', 'hour', $this->hour])
            ->andFilterWhere(['like', 'day', $this->day])
            ->andFilterWhere(['like', 'month', $this->month])
            ->andFilterWhere(['like', 'week', $this->week])
            ->andFilterWhere(['like', 'command', $this->command]);

        return $dataProvider;
    }
}
