<?php


namespace TaskForce\App\Models;


class StartAction extends AbstractAction
{
    const TITLE = 'Начать';
    const CODE_NAME = 'start';

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
        return $userId === $customerId;
    }
}
