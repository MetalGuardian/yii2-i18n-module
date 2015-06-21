<?php

namespace metalguardian\i18n\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use metalguardian\i18n\models\SourceMessage;

/**
 * SourceMessageSearch represents the model behind the search form about `metalguardian\i18n\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message'], 'safe'],
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
        $query = SourceMessage::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
