<?php
use PHPUnit\Framework\TestCase;
use TaskForce\App\Models\Task;


class TaskTest extends TestCase
{
    public function testGetCurrentStatus(): void
    {
        $customerId = 1;

        $task = new Task(Task::STATUS_NEW, $customerId);
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

        $task = new Task(Task::STATUS_NEW, $customerId);
        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([Task::ACTION_CANCEL], $availableActions);

        $task = new Task(Task::STATUS_NEW, $customerId, $executorId);
        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([Task::ACTION_RESPOND], $availableActions);

        $task = new Task(Task::STATUS_NEW, $customerId, $executorId);
        $availableActions = $task->getAvailableActionsByStatusAndUserId($randomId);
        $this->assertEquals([Task::ACTION_RESPOND], $availableActions);

        $task = new Task(Task::STATUS_IN_PROGRESS, $customerId);
        $availableActions = $task->getAvailableActionsByStatusAndUserId($customerId);
        $this->assertEquals([Task::ACTION_FINISH], $availableActions);

        $task = new Task(Task::STATUS_IN_PROGRESS, $customerId, $executorId);
        $availableActions = $task->getAvailableActionsByStatusAndUserId($executorId);
        $this->assertEquals([Task::ACTION_REFUSE], $availableActions);

        $task = new Task(Task::STATUS_IN_PROGRESS, $customerId, $executorId);
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
