<?php

namespace metalguardian\i18n\models;

use metalguardian\i18n\components\I18n;
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
    public $translation;

    /**
     * @return array
     * @throws InvalidConfigException
     */
    public function getColumns()
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

        /** @var \metalguardian\i18n\components\I18n $i18n */
        $i18n = Yii::$app->getI18n();
        if (!($i18n instanceof I18n)) {
            throw new InvalidConfigException(Module::t('I18n component have to be instance of metalguardian\i18n\components\I18n'));
        }
        foreach ($i18n->languages as $language) {
            $columns[] = [
                'attribute' => 'translation',
                'label' => Module::t('Translation[{language}]', ['language' => $language]),
                'value' => function (SourceMessage $data) use ($language) {
                    return isset($data->messages[$language]) ? Html::encode($data->messages[$language]->translation) : null;
                },
                'filter' => Html::activeTextInput($this, 'translation[' . $language . ']', ['class' => 'form-control']),
            ];
        }

        $columns[] = [
            'attribute' => 'category',
            'filter' => \yii\helpers\ArrayHelper::map(SourceMessage::getCategories(), 'category', 'category'),
            'options' => ['class' => 'col-sm-1'],
        ];

        return $columns;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'message', 'translation'], 'safe'],
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

        $translations = $this->formatTranslations($this->translation);

        $or = ['or'];
        foreach ($translations as $key => $texts) {
            $or[] = ['and',
                ['message.language' => $key],
                ['or like', 'message.translation', $texts]
            ];
        }
        $query->andWhere($or);


        return $dataProvider;
    }

    private function formatTranslations($translation)
    {
        $translation = array_filter((array) $translation);
        $data = [];
        foreach ($translation as $key =>  $one) {
            $words = preg_split('/[\s,]+/', $one);
            foreach ($words as $word) {
                $data[$key][] = $word;
            }
        }

        return $data;
    }
}
