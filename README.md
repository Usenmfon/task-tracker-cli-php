# Task Tracker CLI

A simple cross‑platform command‑line tool for tracking the tasks you need to do, are working on, or have completed. All data is stored locally in **tasks.json** in the same folder—no database required.

---

## 1. Prerequisites

| Requirement | Version                  | Notes                |
| ----------- | ------------------------ | -------------------- |
| PHP (CLI)   | 7.4 or newer             | Verify with `php -v` |
| OS          | Windows, macOS, or Linux | Platform‑agnostic    |

No external libraries or Composer dependencies are needed.

---

## 2. Quick Start (run from the cloned folder)

```bash
git clone https://github.com/Usenmfon/task‑tracker‑cli.git
cd task‑tracker‑cli
php task‑cli.php list          # creates tasks.json on first run
```

---

## 3. Installing Globally (optional)

| Platform                                                           | Steps      |
| ------------------------------------------------------------------ | ---------- |
| **Linux / macOS**                                                  | \`\`\`bash |
| chmod +x task‑cli.php              # make it executable            |            |
| sudo mv task‑cli.php /usr/local/bin/task # rename & move to \$PATH |            |
| task add 'Buy groceries'           # run from anywhere             |            |

````|
| **Windows (PowerShell)** | ```powershell
# Create a bin folder in your home directory (only once)
New-Item -ItemType Directory -Force "$env:USERPROFILE\bin"
Copy-Item .\task-cli.php "$env:USERPROFILE\bin\task"
# Add %USERPROFILE%\bin to PATH once via System Settings
function task { php $env:USERPROFILE\bin\task @args }
# Now you can run:
 task add 'Buy groceries'
``` |

> **Tip:** You can also create a simple `task.bat` wrapper that calls `php %~dp0task-cli.php %*` and place it in a folder that’s on your PATH.

---

## 4. Usage

````

php task‑cli.php <command> \[arguments]

```

| Command | Example | Description |
|---------|---------|-------------|
| `add` | `php task‑cli.php add 'Buy milk'` | Create a new task (status = todo). |
| `update` | `php task‑cli.php update 2 'Buy milk and bread'` | Change a task description. |
| `delete` | `php task‑cli.php delete 2` | Permanently remove a task. |
| `mark-in-progress` | `php task‑cli.php mark-in-progress 3` | Set status to **in‑progress**. |
| `mark-done` | `php task‑cli.php mark-done 3` | Set status to **done**. |
| `list` | `php task‑cli.php list` | Show all tasks. |
| `list todo|in-progress|done` | `php task‑cli.php list done` | Filter by status. |

---

## 5. Where Your Data Lives

The script automatically creates **tasks.json** in the same directory on first run. Back this file up if you need to preserve your tasks.

---

## 6. Troubleshooting

| Issue | Fix |
|-------|-----|
| `php` command not found | Ensure PHP is on your PATH. |
| `Permission denied` on Linux/macOS | Run `chmod +x task‑cli.php` or use `sudo`. |
| Corrupted `tasks.json` | Delete or repair the file; a fresh one will be created automatically. |

---

## 7. Contributing

Feel free to open issues or pull requests—suggestions and improvements are welcome!

---

## 8. License

MIT

```
