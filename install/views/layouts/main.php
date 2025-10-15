<?php
/**
 * @Author: Wang chunsheng  email:2192138785@qq.com
 * @Date:   2021-01-20 20:41:47
 * @Last Modified by:   Wang chunsheng  email:2192138785@qq.com
 * @Last Modified time: 2022-06-28 16:52:09
 */

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Menu;

/* @var $this View */
/* @var $content string */

//$asset = yii\gii\GiiAsset::register($this);
?>
<?php $this->beginPage(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags(); ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>店滴云CMS 安装向导</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f9;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1200px;
        }
        .container h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }

        .form-group-flex {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
        .message {
            margin-top: 15px;
            margin-bottom: 15px;
            text-align: center;
            color: red;
        }
        .step {
            height: calc(100vh - 100px);
        }
        .step-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .steptab {
            flex: 1;
            text-align: center;
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            position: relative;
        }
        #check-env-table,#check-file-table{
            width: 100%;
        }
        .steptab.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .steptab.active:not(:last-child)::after {
            background-color: #007bff;
        }


        #install-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .step {
            margin-bottom: 20px;
        }

        .pr-title {
            margin-bottom: 10px;
        }

        .pr-title h3 {
            color: #333;
        }

        .check-box {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .check-box th {
            background-color: #f8f8f8;
            padding: 8px;
            text-align: left;
        }

        .check-box td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .check-box tr:last-child td {
            border-bottom: none;
        }

        .ico.pass {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: #4caf50;
            border-radius: 50%;
            margin-right: 5px;
        }

        .ico.error {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-color: #f44336;
            border-radius: 50%;
            margin-right: 5px;
        }

        .messages {
            list-style-type: none;
            padding: 0;
            margin: 20px 0;
        }

        .messages li {
            padding: 8px;
            background-color: #f8f8f8;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 5px;
        }

        .messages li.error {
            background-color: #ffebee;
            border-color: #ffcdd2;
            color: #f44336;
        }

        .messages li.success {
            background-color: #e8f5e9;
            border-color: #c8e6c9;
            color: #4caf50;
        }

        .btn-box {
            text-align: right;
        }

        .btn-normal {
            padding: 10px 20px;
            border: none;
            background-color: #007bff;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .btn-normal:hover {
            background-color: #0056b3;
        }

        .btn-normal.ml10 {
            margin-left: 10px;
        }

        .form-group {
            margin-top: 20px;
        }

        .form-group button {
            padding: 10px 20px;
            border: none;
            background-color: #4caf50;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #45a049;
        }

        .message {
            margin-top: 20px;
            text-align: center;
            color: #f44336;
        }
    </style>
</head>
<body>
<div class="page-container">
    <?php $this->beginBody(); ?>
    <div class="container" style="margin-top: 20px;">
        <?php if (Yii::$app->session->hasFlash('error')): ?>
            <div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <?= Yii::$app->session->getFlash('error') ?>
            </div>
        <?php endif; ?>
        <!--      在这里增加系统安装的步骤-->

        <?= $content; ?>
    </div>
    <div class="footer-fix"></div>
</div>
<?php $this->endBody(); ?>
</body>
</html>
<?php $this->endPage(); ?>
