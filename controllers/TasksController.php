<?php

namespace app\controllers;

use yii\db\Query;
use app\models\Task;

class TasksController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $query = new Query();
        $query->select([
            'tasks.id', 'title', 'description', 'budget', 'expiry_dt', 'tasks.created_at',
            'categories.name AS category_name', 'cities.name AS city_name'
        ]);
        $query->from('tasks');

        $where = ['status' => 1];
        $query->where($where);

        $query->join('LEFT JOIN', 'categories', 'categories.id = tasks.category_id');
        $query->join('LEFT JOIN', 'cities', 'cities.id = tasks.city_id');

        $query->limit(10)->orderBy('tasks.created_at DESC');
        $tasks = $query->all();

        return $this->render('index', ['tasks' => $tasks]);
    }

}
