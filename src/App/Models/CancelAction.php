<?php


namespace TaskForce\App\Models;


class CancelAction extends AbstractAction
{
    const TITLE = 'Отменить';
    const CODE_NAME = 'cancel';

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
