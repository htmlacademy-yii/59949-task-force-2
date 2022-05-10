<?php


namespace TaskForce\App\Models;


class CancelAction extends AbstractAction
{
    const TITLE = 'Отменить';
    const CODE_NAME = 'cancel';

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
