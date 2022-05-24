<?php declare(strict_types=1);
namespace TaskForce\App\Models;


use TaskForce\App\Exceptions\ParamNotExistsException;

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
        $this->checkStatusExists($status);

        $this->status = $status;
        $this->customerId = $customerId;
        $this->executorId = $executorId;
    }

    private function getStatusesMap(): array
    {
        return self::STATUSES_MAP;
    }

    private function getActionsMap(): array
    {
        return self::ACTIONS_MAP;
    }

    public function getCurrentStatus(): ?string
    {
        return $this->status;
    }

    public function getNewStatusByAction(string $actionType): ?string
    {
        $this->checkActionExists($actionType);

        $statusByActionList = [
            self::ACTION_RESPOND => self::STATUS_NEW,
            self::ACTION_START => self::STATUS_IN_PROGRESS,
            self::ACTION_REFUSE => self::STATUS_FAILED,
            self::ACTION_CANCEL => self::STATUS_CANCELED,
            self::ACTION_FINISH => self::STATUS_DONE
        ];

        return $statusByActionList[$actionType] ?? null;
    }

    public function getAvailableActionsByStatusAndUserId(int $userId): array
    {
        $actions = [];

        $respondAction = new RespondAction;
        $cancelAction = new CancelAction;
        $refuseAction = new RefuseAction;
        $finishAction = new FinishAction;

        switch ($this->status):
            case self::STATUS_NEW:
                if ($cancelAction->checkUserRights($userId, $this->executorId, $this->customerId)) {
                    $actions = [$cancelAction->getCodeName()];
                } else if ($respondAction->checkUserRights($userId, $this->executorId, $this->customerId)) {
                    $actions = [$respondAction->getCodeName()];
                }
                break;
            case self::STATUS_IN_PROGRESS:
                if ($finishAction->checkUserRights($userId, $this->executorId, $this->customerId)) {
                    $actions = [$finishAction->getCodeName()];
                } else if ($refuseAction->checkUserRights($userId, $this->executorId, $this->customerId)) {
                    $actions = [$refuseAction->getCodeName()];
                }
                break;
            default:
                $actions = [];
        endswitch;

        return $actions;
    }

    /**
     * @throws ParamNotExistsException
     */
    private function checkStatusExists(string $status): void
    {
        if (!array_key_exists($status, self::STATUSES_MAP)) {
            throw new ParamNotExistsException("Передан некорректный статус");
        }
    }

    /**
     * @throws ParamNotExistsException
     */
    private function checkActionExists(string $action): void
    {
        if (!array_key_exists($action, self::ACTIONS_MAP)) {
            throw new ParamNotExistsException("Передано некорректное действие");
        }
    }
}
