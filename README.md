# Logger

A lightweight [PocketMine-MP 5](https://github.com/pmmp/PocketMine-MP) plugin that logs **every chat message and command** a player sends into a **per-player JSON file** inside `plugin_data`.

**Author:** RohaM
**API:** 5.0.0
**License:** MIT

---

## ✨ Features

- 📝 Logs **all chat messages** sent by players
- ⌨️ Logs **all commands** executed by players (including plugin commands)
- 📁 Creates **one JSON file per player**, named after the player (e.g. `RohaM.json`)
- 💾 Optional auto-save interval + save-on-quit + save-on-disable
- 🔒 Atomic writes (temp file + `rename`) to prevent file corruption
- 🧹 Built-in `/logger` command for viewing / clearing / reloading logs
- ⚡ Lightweight, no database required
- 🧩 Clean, multi-file namespaced code (`RohaM\Logger\*`)
- 🛠️ Fully configurable (`config.yml`)

---

## 📂 Data Structure

Files are stored at:

```
plugins/Logger/plugin_data/players/<PlayerName>.json
```

Example — `plugin_data/players/RohaM.json`:

```json
[
    {
        "type": "chat",
        "content": "Hello everyone!",
        "time": "2025-01-15 21:34:11",
        "timestamp": 1736969651
    },
    {
        "type": "command",
        "content": "/gamemode creative",
        "time": "2025-01-15 21:34:25",
        "timestamp": 1736969665
    }
]
```

| Field       | Description                            |
|-------------|----------------------------------------|
| `type`      | `chat` or `command`                    |
| `content`   | The raw message or command text        |
| `time`      | Human-readable timestamp               |
| `timestamp` | Unix timestamp (for external tooling)  |

---

## 🚀 Installation

1. Download or clone this repository.
2. Put the `Logger` folder inside your server's `plugins/` directory.
3. Restart the server.
4. Logs will automatically appear at:
   ```
   plugins/Logger/plugin_data/players/<PlayerName>.json
   ```

---

## ⚙️ Commands

| Command                          | Description                     | Permission       |
|----------------------------------|---------------------------------|------------------|
| `/logger view <player>`          | Show log entry count + filepath | `logger.command` |
| `/logger clear <player>`         | Delete a player's log file      | `logger.command` |
| `/logger reload`                 | Reload `config.yml`             | `logger.command` |

Aliases: `/logs`, `/log`

---

## 🔧 Configuration (`config.yml`)

```yaml
log-chat: true
log-commands: true
save-interval: 300
date-format: "Y-m-d H:i:s"
max-entries: 0
ignored-players: []
ignored-commands: []
```

- `save-interval` — seconds between auto-saves (`0` to disable).
- `max-entries` — maximum entries kept per player (`0` = unlimited).
- `ignored-commands` — command names without leading `/`.

---

## 🧱 Architecture

| Class                    | Namespace                          | Responsibility                                            |
|--------------------------|------------------------------------|-----------------------------------------------------------|
| `Loader`                 | `RohaM\Logger`                     | Plugin bootstrap, config, command & event registration    |
| `LoggerManager`          | `RohaM\Logger\managers`            | Holds all `PlayerLogger` instances, filters, save-all     |
| `PlayerLogger`           | `RohaM\Logger\loggers`             | Load / append / atomic-save one player's JSON file        |
| `PlayerEventListener`    | `RohaM\Logger\listeners`           | Handles chat, command-preprocess, and quit events         |
| `AutoSaveTask`           | `RohaM\Logger\tasks`               | Periodically flushes dirty logs                           |
| `LoggerCommand`          | `RohaM\Logger\commands`            | Implements `/logger` subcommands                          |
| `FileUtils`              | `RohaM\Logger\utils`               | Atomic file-write helper                                  |

---

## ✅ Requirements

- PocketMine-MP **5.0.0+**
- PHP **8.1+**

---

## 📜 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.