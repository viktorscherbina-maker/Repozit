<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Repozit — Задачи</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #0f172a;
            color: #e2e8f0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .container { max-width: 600px; width: 100%; }
        h1 {
            font-size: 2rem;
            margin-bottom: 1.5rem;
            color: #38bdf8;
        }
        .add-form {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .add-form input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid #334155;
            border-radius: 8px;
            background: #1e293b;
            color: #e2e8f0;
            font-size: 1rem;
            outline: none;
            transition: border-color 0.2s;
        }
        .add-form input:focus { border-color: #38bdf8; }
        .add-form button {
            padding: 0.75rem 1.5rem;
            background: #38bdf8;
            color: #0f172a;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .add-form button:hover { background: #7dd3fc; }
        .tasks { list-style: none; }
        .task {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #1e293b;
            border-radius: 8px;
            margin-bottom: 0.5rem;
            transition: background 0.2s;
        }
        .task:hover { background: #334155; }
        .task.done .task-title { text-decoration: line-through; opacity: 0.5; }
        .task-title { flex: 1; font-size: 1rem; cursor: pointer; }
        .task-delete {
            background: none;
            border: none;
            color: #f87171;
            font-size: 1.2rem;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.2s;
        }
        .task-delete:hover { opacity: 1; }
        .empty {
            text-align: center;
            color: #64748b;
            padding: 2rem;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Repozit</h1>
        <form class="add-form" id="addForm">
            <input type="text" id="taskInput" placeholder="Новая задача..." autofocus>
            <button type="submit">Добавить</button>
        </form>
        <ul class="tasks" id="taskList"></ul>
    </div>

    <script>
        const taskList = document.getElementById('taskList');
        const addForm = document.getElementById('addForm');
        const taskInput = document.getElementById('taskInput');

        async function loadTasks() {
            const res = await fetch('/api/tasks');
            const tasks = await res.json();
            renderTasks(tasks);
        }

        function renderTasks(tasks) {
            if (tasks.length === 0) {
                taskList.innerHTML = '<li class="empty">Нет задач. Добавьте первую!</li>';
                return;
            }
            taskList.innerHTML = tasks.map(t => `
                <li class="task ${t.done ? 'done' : ''}" data-id="${t.id}">
                    <span class="task-title" onclick="toggleTask(${t.id})">${escapeHtml(t.title)}</span>
                    <button class="task-delete" onclick="deleteTask(${t.id})">&#x2715;</button>
                </li>
            `).join('');
        }

        function escapeHtml(text) {
            const d = document.createElement('div');
            d.textContent = text;
            return d.innerHTML;
        }

        addForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const title = taskInput.value.trim();
            if (!title) return;
            await fetch('/api/tasks', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ title })
            });
            taskInput.value = '';
            loadTasks();
        });

        async function toggleTask(id) {
            await fetch('/api/tasks/toggle', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            loadTasks();
        }

        async function deleteTask(id) {
            await fetch('/api/tasks/delete', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
            loadTasks();
        }

        loadTasks();
    </script>
</body>
</html>
