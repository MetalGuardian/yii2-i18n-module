<?php

use metalguardian\i18n\models\SourceMessage;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel metalguardian\i18n\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Source Messages';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'id',
                'filter' => false
            ],
            [
                'attribute' => 'message',
                'format' => 'raw',
                'value' => function (SourceMessage $model) {
                    return Html::a(Html::encode($model->message), ['update', 'id' => $model->id], ['data-pjax' => 0]);
                }
            ],
            [
                'attribute' => 'category',
                'filter' => \yii\helpers\ArrayHelper::map(SourceMessage::getCategories(), 'category', 'category')
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
