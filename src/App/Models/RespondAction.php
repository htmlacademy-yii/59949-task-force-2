<?php


namespace TaskForce\App\Models;


class RespondAction extends AbstractAction
{
    const TITLE = 'Откликнуться';
    const CODE_NAME = 'respond';

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
