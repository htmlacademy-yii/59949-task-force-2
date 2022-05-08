<?php


namespace TaskForce\App\Models;


class RefuseAction extends AbstractAction
{
    const TITLE = 'Отказаться';
    const CODE_NAME = 'refuse';

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
        return $userId === $executorId;
    }
}
