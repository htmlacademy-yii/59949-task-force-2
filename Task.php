<?php


class Task
{
    public $status;

    private $customerId;
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
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_REFUSE => 'Отказаться',
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_FINISH => 'Завершить'
    ];

    const STATUSES_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено'
    ];

    public function __construct(int $customerId, ?int $executorId = null)
    {
        $this->customerId = $customerId;
        $this->executorId = $executorId;
        $this->status = self::STATUS_NEW;
    }

    private function getStatusesMap()
    {
        return self::STATUSES_MAP;
    }

    private function getActionsMap()
    {
        return self::ACTIONS_MAP;
    }

    public function getCurrentStatus()
    {
        return $this->status;
    }

    public function getNewStatusByAction(string $actionType)
    {
        $statusByActionList = [
            self::ACTION_RESPOND => self::STATUS_IN_PROGRESS,
            self::ACTION_REFUSE => self::STATUS_FAILED,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_FINISH => self::STATUS_DONE
        ];

        return $statusByActionList[$actionType] ?? null;
    }

    public function getAvailableActionsByStatusAndRoleType(string $status, string $roleType)
    {
        $customerActionsList = [
            self::STATUS_NEW => [
                self::ACTION_CANCEL
            ],
            self::STATUS_IN_PROGRESS => [
                self::ACTION_FINISH
            ],
            self::STATUS_DONE => [],
            self::STATUS_FAILED => [],
            self::STATUS_CANCELED => []
        ];

        $executorActionsList = [
            self::STATUS_NEW => [
                self::ACTION_RESPOND
            ],
            self::STATUS_IN_PROGRESS => [
                self::ACTION_REFUSE
            ],
            self::STATUS_DONE => [],
            self::STATUS_FAILED => [],
            self::STATUS_CANCELED => []
        ];

        if ($roleType === 'customer') {
            return $customerActionsList[$status] ?? null;
        }
        if ($roleType === 'executor') {
            return $executorActionsList[$status] ?? null;
        }

        return null;
    }
}
