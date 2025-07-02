#!/usr/bin/env php
<?php
/**
 * Task Tracker CLI
 * Usage examples:
 *   php task-cli.php add "Buy groceries"
 *   php task-cli.php update 1 "Buy groceries and cook dinner"
 *   php task-cli.php delete 1
 *   php task-cli.php mark-in-progress 1
 *   php task-cli.php mark-done 1
 *   php task-cli.php list
 *   php task-cli.php list done
 */

const TASK_FILE = __DIR__ . DIRECTORY_SEPARATOR . 'tasks.json';

function loadTasks(): array
{
    if(!file_exists(TASK_FILE)){
        file_put_contents(TASK_FILE, json_encode([]));
    }

    $json = file_get_contents(TASK_FILE);
    $tasks = json_decode($json, true);

    return is_array($tasks) ? $tasks : [];

}

function saveTasks(array $tasks): void
{
    file_put_contents(TASK_FILE, json_encode($tasks, JSON_PRETTY_PRINT));
}

function nextId(array $tasks): int
{
    $ids = array_column($tasks, 'id');
    return $ids ? (max($ids) + 1) : 1;
}

function displayTask(array $task): void
{
    echo sprintf(
        "[%d] %-12s %s (created: %s, updated: %s)\n",
        $task['id'],
        $task['status'],
        $task['description'],
        $task['createdAt'],
        $task['updatedAt']
    );
}

function usage(): void
{
    echo "\nTask Tracker CLI\n";
    echo "Usage:\n";
    echo "  php task-cli.php add \"Task description\"\n";
    echo "  php task-cli.php update <id> \"New description\"\n";
    echo "  php task-cli.php delete <id>\n";
    echo "  php task-cli.php mark-in-progress <id>\n";
    echo "  php task-cli.php mark-done <id>\n";
    echo "  php task-cli.php list [todo|in-progress|done]\n\n";
    exit(1);
}

if($argc < 2)
{
    usage();
}

$command = $argv[1];
$tasks = loadTasks();
$now = date('c');

switch ($command)
{
    case 'add':
        if($argc < 3) {
            echo "Error: Missing task description." . PHP_EOL;
            usage();
        }
        $description = $argv[2];
        $id = nextId($tasks);
        $tasks[] = [
            'id' => $id,
            'description' => $description,
            'status' => 'todo',
            'createdAt' => $now,
            'updatedAt' => $now,
        ];
        saveTasks($tasks);
        echo "Task added successfully (ID: $id)" . PHP_EOL;
        break;

    case 'update':
        if($argc < 4) {
            echo "Error: Missing arguments for update." . PHP_EOL;
            usage();
        }
        $id = (int)$argv[2];
        $description = $argv[3];
        $found = false;
        foreach($tasks as &$task) {
            if($task['id'] === $id) {
                $task['description'] = $description;
                $task['updatedAt'] = $now;
                $found = true;
                break;
            }
        }
        if(!$found) {
            echo "Task with ID $id not found." . PHP_EOL;
            exit(1);
        }
        saveTasks($tasks);
        echo "Task updated successfully." . PHP_EOL;
        break;
    
    case 'delete':
        if($argc < 3) {
            echo "Error: Missing task ID for deletion." . PHP_EOL;
            usage();
        }
        $id = (int)$argv[2];
        $originalCount = count($tasks);
        $tasks = array_filter($tasks, fn($task) => $task['id'] !== $id);
        if(count($tasks) === $originalCount) {
            echo "Task with ID $id not found." . PHP_EOL;
            exit(1);
        }
        saveTasks($tasks);
        echo "Task deleted successfully." . PHP_EOL;
        break;
    
    case 'mark-in-progress':
    case 'mark-done':
        if($argc < 3) {
            echo "Error: Missing task ID." . PHP_EOL;
            usage();
        }
        $id = (int)$argv[2];
        $status = $command === 'mark-in-progress' ? 'in-progress' : 'done';
        $found = false;
        foreach ($tasks as &$task) {
            if($task['id'] === $id) {
                $task['status'] = $status;
                $task['updatedAt'] = $now;
                $found = true;
                break;
            }
        }
        unset($task);

        if(!$found) {
            echo "Task with ID $id not found." . PHP_EOL;
            exit(1);
        }
        saveTasks($tasks);
        echo "Task marked as $status successfully." . PHP_EOL;
        break;

    case 'list':
        $filter = $argv[2] ?? null;
        $validStatuses = [null, 'todo', 'in-progress', 'done'];
        if(!in_array($filter, $validStatuses, true)) {
            echo "Error: Invalid status filter. Use todo, in-progress, done or leave blank." . PHP_EOL;
            exit(1);
        }
        $filteredTasks = $tasks;
        if($filter){
            $filteredTasks = array_filter($tasks, fn($task) => $task['status'] === $filter);
        }

        if(empty($filteredTasks)){
            echo "No tasks found." . PHP_EOL;
            exit(0);
        }

        echo "\n--- Task List ---\n";
        foreach($filteredTasks as $task) {
            displayTask($task);
        }
        break;

        default:
            echo "Error: Unknown command '$command'." . PHP_EOL;
            usage();
}