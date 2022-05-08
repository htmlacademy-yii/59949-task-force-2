<?php


namespace TaskForce\App\Models;


class FinishAction extends AbstractAction
{
    const TITLE = 'Завершить';
    const CODE_NAME = 'finish';

    public function getTitle()
    {
        return self::TITLE;
    }

    public function getCodeName()
    {
        return self::CODE_NAME;
    }

    public function checkUserRights($userId, $executorId, $customerId)
    {
        return $userId === $customerId;
    }
}
