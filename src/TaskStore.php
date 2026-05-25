<?php

namespace App;

class TaskStore
{
    private string $file;

    public function __construct(?string $file = null)
    {
        $this->file = $file ?? __DIR__ . '/../data/tasks.json';
        $dir = dirname($this->file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    public function all(): array
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $data = json_decode(file_get_contents($this->file), true);
        return is_array($data) ? $data : [];
    }

    public function add(string $title): array
    {
        $tasks = $this->all();
        $task = [
            'id' => count($tasks) > 0 ? max(array_column($tasks, 'id')) + 1 : 1,
            'title' => $title,
            'done' => false,
            'created_at' => date('Y-m-d H:i:s'),
        ];
        $tasks[] = $task;
        $this->save($tasks);

        return $task;
    }

    public function toggle(int $id): ?array
    {
        $tasks = $this->all();
        foreach ($tasks as &$task) {
            if ($task['id'] === $id) {
                $task['done'] = !$task['done'];
                $this->save($tasks);
                return $task;
            }
        }

        return null;
    }

    public function delete(int $id): bool
    {
        $tasks = $this->all();
        $filtered = array_values(array_filter($tasks, fn ($t) => $t['id'] !== $id));

        if (count($filtered) === count($tasks)) {
            return false;
        }

        $this->save($filtered);
        return true;
    }

    private function save(array $tasks): void
    {
        file_put_contents($this->file, json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
