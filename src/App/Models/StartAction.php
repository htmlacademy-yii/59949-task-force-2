<?php


namespace TaskForce\App\Models;


class StartAction extends AbstractAction
{
    const TITLE = 'Начать';
    const CODE_NAME = 'start';

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
