<?php

namespace Tests;

use App\TaskStore;
use PHPUnit\Framework\TestCase;

class TaskStoreTest extends TestCase
{
    private string $tmpFile;
    private TaskStore $store;

    protected function setUp(): void
    {
        $this->tmpFile = sys_get_temp_dir() . '/repozit_test_' . uniqid() . '.json';
        $this->store = new TaskStore($this->tmpFile);
    }

    protected function tearDown(): void
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
    }

    public function testEmptyStoreReturnsEmptyArray(): void
    {
        $this->assertEquals([], $this->store->all());
    }

    public function testAddTask(): void
    {
        $task = $this->store->add('Первая задача');

        $this->assertEquals(1, $task['id']);
        $this->assertEquals('Первая задача', $task['title']);
        $this->assertFalse($task['done']);
        $this->assertArrayHasKey('created_at', $task);
    }

    public function testAddMultipleTasks(): void
    {
        $this->store->add('Задача 1');
        $this->store->add('Задача 2');
        $this->store->add('Задача 3');

        $all = $this->store->all();
        $this->assertCount(3, $all);
        $this->assertEquals(1, $all[0]['id']);
        $this->assertEquals(2, $all[1]['id']);
        $this->assertEquals(3, $all[2]['id']);
    }

    public function testToggleTask(): void
    {
        $this->store->add('Задача');

        $toggled = $this->store->toggle(1);
        $this->assertTrue($toggled['done']);

        $toggledBack = $this->store->toggle(1);
        $this->assertFalse($toggledBack['done']);
    }

    public function testToggleNonexistentTask(): void
    {
        $this->assertNull($this->store->toggle(999));
    }

    public function testDeleteTask(): void
    {
        $this->store->add('Удалить меня');

        $this->assertTrue($this->store->delete(1));
        $this->assertCount(0, $this->store->all());
    }

    public function testDeleteNonexistentTask(): void
    {
        $this->assertFalse($this->store->delete(999));
    }
}
