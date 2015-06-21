<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model metalguardian\i18n\models\SourceMessage */

$this->title = 'Update Source Message: ' . ' ' . $model->message;
$this->params['breadcrumbs'][] = ['label' => 'Source Messages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->message;
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="source-message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="source-message-form">

        <?php $form = ActiveForm::begin(); ?>

        <?php foreach ($model->messages as $language => $message) : ?>
            <?= $form->field($message, '[' . $language . ']translation', ['options' => ['class' => 'form-group col-sm-6']])->textarea()->label($language) ?>
        <?php endforeach; ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
