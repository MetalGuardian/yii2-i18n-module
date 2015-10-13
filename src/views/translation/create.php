<?php

use metalguardian\i18n\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model metalguardian\i18n\models\SourceMessage */

$this->title = Module::t('Create Translation');
$this->params['breadcrumbs'][] = ['label' => Module::t('Source Messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Module::t('Create');
?>
<div class="source-message-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="source-message-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'category')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'message')->textarea() ?>

        <div class="form-group">
            <?= Html::submitButton(Module::t('Update'), ['class' => 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
