<?php


namespace TaskForce\App\Models;


class FinishAction extends AbstractAction
{
    const TITLE = 'Завершить';
    const CODE_NAME = 'finish';

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
