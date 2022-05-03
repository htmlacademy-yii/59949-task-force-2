<?php


namespace TaskForce\App\Models;


abstract class AbstractAction
{
    abstract protected function getTitle();

    abstract protected function getCodeName();

    abstract protected function checkUserRights($userId, $executorId, $customerId);
}
