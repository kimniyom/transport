<?php

use yii\helpers\Html;
//use yii\widgets\ActiveForm;
use kartik\form\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customer-form">

    <?php
    $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'formConfig' => ['labelSpan' => 2, 'deviceSize' => ActiveForm::SIZE_SMALL]
    ]);
    ?>
    <?= $form->field($model, 'cus_id')->textInput(['maxlength' => true, 'value' => $cus_id, 'readonly' => 'readonly']) ?>
    <?= $form->field($model, 'company')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'tax_number')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'address')->textarea(['maxlength' => true, 'rows' => 5]) ?>

    <?= $form->field($model, 'tel')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'detail')->textarea(['rows' => 5]) ?>
    <div class="form-group">
        <div class="col-md-2 col-lg-2"></div>
        <div class="col-sm-10 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? '<i class="fa fa-save"></i> บันทึกข้อมูล' : '<i class="fa fa-pencil"></i> แก้ไขข้อมูล', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
            <?= Html::resetButton('<i class="fa fa-remove"></i> ยกเลิก', ['class' => 'btn btn-danger']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
