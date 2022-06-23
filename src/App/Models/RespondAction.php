<?php


namespace TaskForce\App\Models;


class RespondAction extends AbstractAction
{
    const TITLE = 'Откликнуться';
    const CODE_NAME = 'respond';

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
