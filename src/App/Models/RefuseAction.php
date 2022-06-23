<?php


namespace TaskForce\App\Models;


class RefuseAction extends AbstractAction
{
    const TITLE = 'Отказаться';
    const CODE_NAME = 'refuse';

    public function getTitle(): string
    {
        return self::TITLE;
    }

    public function getCodeName(): string
    {
        return self::CODE_NAME;
    }

    public function checkUserRights($userId, $executorId, $customerId): bool
    {
        return $userId === $executorId;
    }
}
