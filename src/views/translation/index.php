<?php

use metalguardian\i18n\models\SourceMessage;
use metalguardian\i18n\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel metalguardian\i18n\models\SourceMessageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('Source Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="source-message-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => $searchModel->getColumns(),
    ]); ?>
    <?php Pjax::end(); ?>
</div>
