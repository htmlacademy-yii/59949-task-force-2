<?php


class Task
{
    public $status;

    private $ownerId;
    private $executorId;

    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'inProgress';
    const STATUS_CANCELED = 'canceled';
    const STATUS_DONE = 'done';
    const STATUS_FAILED = 'failed';

    const ACTION_RESPOND = 'respond';
    const ACTION_REFUSE = 'refuse';
    const ACTION_CANCEL = 'cancel';
    const ACTION_FINISH = 'finish';

    const ACTIONS_MAP = [
        'respond' => 'Откликнуться',
        'refuse' => 'Отказаться',
        'cancel' => 'Отменить',
        'finish' => 'Завершить'
    ];

    const STATUSES_MAP = [
        'new' => 'Новое',
        'inProgress' => 'В работе',
        'canceled' => 'Отменено',
        'done' => 'Выполнено',
        'failed' => 'Провалено'
    ];

    public function __construct(int $ownerId, ?int $executorId)
    {
        $this->ownerId = $ownerId;
        $this->executorId = $executorId;
        $this->status = self::STATUS_NEW;
    }

    public function getStatusesMap()
    {
        return self::STATUSES_MAP;
    }

    public function getActionsMap()
    {
        return self::ACTIONS_MAP;
    }

    public function getCurrentStatus()
    {
        return $this->status;
    }

    public function getAvailableActions()
    {
        return null;
    }

    private function setStatusByRoleAction(string $roleType, string $actionType)
    {
        if ($roleType === 'owner') {
            if ($this->getCurrentStatus() === self::STATUS_NEW && $actionType === self::ACTIONS_MAP['cancel']) {
                $this->status = self::STATUS_CANCELED;
            }
            if ($this->getCurrentStatus() === self::STATUS_IN_PROGRESS && $actionType === self::ACTIONS_MAP['finish']) {
                $this->status = self::STATUS_DONE;
            }
        }
        if ($roleType === 'executor') {
            if ($this->getCurrentStatus() === self::STATUS_NEW && $actionType === self::ACTIONS_MAP['respond']) {
                $this->status = self::STATUS_IN_PROGRESS;
            }
            if ($this->getCurrentStatus() === self::STATUS_IN_PROGRESS && $actionType === self::ACTIONS_MAP['refuse']) {
                $this->status = self::STATUS_FAILED;
            }
        }

        return $this->getCurrentStatus();
    }
}
