<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model addons\diandi_stock\models\DiandiStockSupplier */

$this->title = '添加 Diandi Stock Supplier';
$this->params['breadcrumbs'][] = ['label' => 'Diandi Stock Suppliers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_tab') ?>

<div class="firetech-main">
    <div class="panel panel-default">
        <div class="box-body">
            <div class="diandi-stock-supplier-create">

                <?= $this->render('_form', [
                'model' => $model,
                ]) ?>

            </div>
        </div>
    </div>
</div>