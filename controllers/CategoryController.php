<?php

namespace app\controllers;

use app\models\Category;
use yii\web\Controller;

class CategoryController extends Controller
{
    public function actionIndex()
    {
        $category = Category::findOne(1);

        if ($category) {
            $name = $category->name;
            var_dump($name);
        }
    }
}
