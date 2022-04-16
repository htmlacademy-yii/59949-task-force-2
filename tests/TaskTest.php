<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../Task.php';


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
}
