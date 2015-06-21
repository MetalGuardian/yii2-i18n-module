<?php

namespace metalguardian\i18n\models;

use metalguardian\i18n\Module;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

/**
 * SourceMessageSearch represents the model behind the search form about `metalguardian\i18n\models\SourceMessage`.
 */
class SourceMessageSearch extends SourceMessage
{
    /**
     * @return array
     * @throws InvalidConfigException
     */
    public static function getColumns()
    {
        $columns = [
            [
                'attribute' => 'id',
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function (SourceMessage $model) {
                    return Html::a(Html::encode($model->message), ['update', 'id' => $model->id], ['data-pjax' => 0]);
                }
            ],
        ];

        /** @var Module $module */
        $module = Module::getInstance();
        if (!$module) {
            throw new InvalidConfigException(Module::t('You need to configure \metalguardian\i18n\Module'));
        }
        foreach ($module->languages as $language) {
            $columns[] = [
                'label' => Module::t('Translation[{language}]', ['language' => $language]),
                'value' => function (SourceMessage $data) use ($language) {
                    return isset($data->messages[$language]) ? Html::encode($data->messages[$language]->translation) : null;
                },
                'filter' => false,
            ];
        }

        $columns[] = [
            'attribute' => 'category',
            'filter' => \yii\helpers\ArrayHelper::map(SourceMessage::getCategories(), 'category', 'category')
        ];

        return $columns;
    }

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
        $query = SourceMessage::find()
            ->joinWith(['messages']);

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
