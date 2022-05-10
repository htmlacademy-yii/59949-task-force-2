<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use TaskForce\App\Exceptions\ParamNotExistsException;
use TaskForce\App\Models\Task;


class TaskTest extends TestCase
{
    public function testThrowExceptionOnWrongParams(): void
    {
        $customerId = 1;

        $this->expectException(ParamNotExistsException::class);
        $this->expectExceptionMessage('Передан некорректный статус');

        new Task('zzzzz', $customerId);

        $task = new Task(Task::STATUS_NEW, $customerId);

        $this->expectException(ParamNotExistsException::class);
        $this->expectExceptionMessage('Передано некорректное действие');

        $task->getNewStatusByAction('zzzzz');
    }

    public function testGetCurrentStatus(): void
    {
        $customerId = 1;

        $task = new Task(Task::STATUS_NEW, $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_NEW, $status);

        $task = new Task(Task::STATUS_IN_PROGRESS, $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_IN_PROGRESS, $status);

        $task = new Task(Task::STATUS_CANCELED, $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_CANCELED, $status);

        $task = new Task(Task::STATUS_DONE, $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_DONE, $status);

        $task = new Task(Task::STATUS_FAILED, $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_FAILED, $status);

        $task = new Task('new', $customerId);
        $status = $task->getCurrentStatus();
        $this->assertEquals(Task::STATUS_NEW, $status);
    }

    public function testGetNewStatusByAction():void
    {
        $customerId = 1;
        $task = new Task(Task::STATUS_NEW, $customerId);

        $newStatus = $task->getNewStatusByAction(Task::ACTION_RESPOND);
        $this->assertEquals(Task::STATUS_NEW, $newStatus);

        $newStatus = $task->getNewStatusByAction(Task::ACTION_START);
        $this->assertEquals(Task::STATUS_IN_PROGRESS, $newStatus);

        $newStatus = $task->getNewStatusByAction(Task::ACTION_REFUSE);
        $this->assertEquals(Task::STATUS_FAILED, $newStatus);

        $newStatus = $task->getNewStatusByAction(Task::ACTION_CANCEL);
        $this->assertEquals(Task::STATUS_CANCELED, $newStatus);

        $newStatus = $task->getNewStatusByAction(Task::ACTION_FINISH);
        $this->assertEquals(Task::STATUS_DONE, $newStatus);

        $newStatus = $task->getNewStatusByAction('missing status');
        $this->assertEquals(null, $newStatus);
    }

    public function testGetAvailableActionsByStatusAndUserId(): void
    {
        $customerId = 1;
        $executorId = 2;
        $randomId = 3;

        $task = new Task(Task::STATUS_NEW, $customerId, $executorId);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([Task::ACTION_CANCEL], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([Task::ACTION_RESPOND], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([], $availableActions);

        $task = new Task(Task::STATUS_IN_PROGRESS, $customerId, $executorId);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([Task::ACTION_FINISH], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([Task::ACTION_REFUSE], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([], $availableActions);

        $task = new Task(Task::STATUS_CANCELED, $customerId, $executorId);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([], $availableActions);

        $task = new Task(Task::STATUS_DONE, $customerId, $executorId);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([], $availableActions);

        $task = new Task(Task::STATUS_FAILED, $customerId, $executorId);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([], $availableActions);

        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([], $availableActions);
    }
}
