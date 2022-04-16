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
}
