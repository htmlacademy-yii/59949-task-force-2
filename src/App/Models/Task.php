<?php
namespace TaskForce\App\Models;


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
    const ACTION_START = 'start';
    const ACTION_FINISH = 'finish';

    const ACTIONS_MAP = [
        self::ACTION_RESPOND => 'Откликнуться',
        self::ACTION_REFUSE => 'Отказаться',
        self::ACTION_CANCEL => 'Отменить',
        self::ACTION_START => 'Начать',
        self::ACTION_FINISH => 'Завершить'
    ];

    const STATUSES_MAP = [
        self::STATUS_NEW => 'Новое',
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_CANCELED => 'Отменено',
        self::STATUS_DONE => 'Выполнено',
        self::STATUS_FAILED => 'Провалено'
    ];

    public function __construct(string $status, int $customerId, ?int $executorId = null)
    {
        $this->status = $status;
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    private function getStatusesMap()
    {
        return self::STATUSES_MAP;
    }

    private function getActionsMap()
    {
        return self::ACTIONS_MAP;
    }

    public function getCurrentStatus(): string
    {
        return $this->status;
    }

    public function getNewStatusByAction(string $actionType)
    {
        $statusByActionList = [
            self::ACTION_RESPOND => self::STATUS_NEW,
            self::ACTION_START => self::STATUS_IN_PROGRESS,
            self::ACTION_REFUSE => self::STATUS_FAILED,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_FINISH => self::STATUS_DONE
        ];

        return $statusByActionList[$actionType] ?? null;
    }

    public function getAvailableActionsByStatusAndUserId(int $userId)
    {
        $actions = [];

        switch ($this->status):
            case self::STATUS_NEW:
                if ($userId === $this->customerId) {
                    $actions = [(new CancelAction)->getCodeName()];
                } else {
                    $actions = [(new RespondAction)->getCodeName()];
                }
                break;
            case self::STATUS_IN_PROGRESS:
                if ($userId === $this->customerId) {
                    $actions = [(new FinishAction)->getCodeName()];
                } else if ($userId === $this->executorId) {
                    $actions = [(new RefuseAction)->getCodeName()];
                }
                break;
            default:
                $actions = [];
        endswitch;

        return $actions;
    }
}
