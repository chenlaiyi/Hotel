<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model addons\diandi_stock\models\DiandiStockSupplier */

$this->title = 'Update Diandi Stock Supplier: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Diandi Stock Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<?= $this->render('_tab') ?>


<div class="firetech-main">
    <div class="panel panel-default">
        <div class="box-body">
            <div class="diandi-stock-supplier-update">


                <?= $this->render('_form', [
                'model' => $model,
                ]) ?>
            </div>
        </div>
    </div>
</div>