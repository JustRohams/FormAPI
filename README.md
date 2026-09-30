# 📘 Binder

> A lightweight, elegant **PocketMine-MP** plugin that lets players save their favorite texts and broadcast them to chat with a single click through a beautiful form UI.

<p align="center">
  <img src="https://img.shields.io/badge/PocketMine--MP-5.0.0+-blue?style=for-the-badge" alt="PocketMine-MP">
  <img src="https://img.shields.io/badge/version-1.0.0-green?style=for-the-badge" alt="Version">
  <img src="https://img.shields.io/badge/license-MIT-orange?style=for-the-badge" alt="License">
  <img src="https://img.shields.io/badge/author-RohaM-purple?style=for-the-badge" alt="Author">
</p>

---

## ✨ Features

- 📝 **Save Custom Texts** — Players can store up to *N* custom messages (configurable).
- 🎨 **Modern Form UI** — Smooth `SimpleForm` & `CustomForm` experience powered by **FormAPI**.
- 🚀 **One-Click Broadcast** — Click any saved entry to instantly broadcast it in chat.
- 💾 **Per-Player Data** — Each player's texts are safely stored in individual JSON files.
- ⚡ **Smart Caching** — Entries are cached in memory for fast access and minimal disk I/O.
- 🔒 **Permission Support** — Controlled via the `binder.use` permission node.
- 🧹 **Clear Command** — Wipe all your saved entries with a single command.
- 🛡️ **Input Sanitization** — Filenames are sanitized to prevent path traversal issues.

---

## 📦 Requirements

| Requirement | Version |
|-------------|---------|
| **PocketMine-MP** | `5.0.0` or higher |
| **PHP** | `8.0+` |
| **FormAPI** | [jojoe77777/FormAPI](https://github.com/jojoe77777/FormAPI) |

---

## 🛠️ Installation

1. **Download** the latest release of `Binder.phar`.
2. Drop the `.phar` file into your server's `plugins/` directory.
3. Make sure **[FormAPI](https://github.com/jojoe77777/FormAPI)** is also installed in `plugins/`.
4. Restart your server.
5. Configure the plugin in `plugins/Binder/config.yml` if needed. ✅

---

## 🎮 Commands & Permissions

### Commands

| Command | Description |
|---------|-------------|
| `/binder` | Opens your personal Binder menu |
| `/binder add` | Opens a form to add a new text |
| `/binder clear` | Clears all your saved texts |

### Permissions

| Permission | Description | Default |
|------------|-------------|---------|
| `binder.use` | Allows usage of the `/binder` command | `true` |

---

## ⚙️ Configuration

```yaml
# plugins/Binder/config.yml

# Maximum number of entries a player can save.
# Set to 0 for unlimited.
max-entries: 20

# If true, saved texts are broadcast to the whole server.
# If false, the text is only sent to the player.
broadcast: true
```

---

## 🧭 How It Works

```
┌──────────────────┐
│  /binder add     │──▶  CustomForm  ──▶  Saved to players/<name>.json
└──────────────────┘
┌──────────────────┐
│  /binder         │──▶  SimpleForm  ──▶  Click a button
└──────────────────┘                        │
                                            ▼
                                  Broadcast or send to chat
```

---

## 📁 Project Structure

```
Binder/
├── plugin.yml
├── resources/
│   └── config.yml
└── src/
    └── RohaM/
        └── Binder/
            ├── Loader.php
            ├── commands/
            │   └── BinderCommand.php
            ├── manager/
            │   └── DataManager.php
            ├── forms/
            │   ├── AddForm.php
            │   └── ListForm.php
            └── utils/
                └── Banner.php
```

---

## 🧩 API Overview

### `DataManager`

| Method | Description |
|--------|-------------|
| `getEntries(string $playerName): array` | Returns all saved entries for a player |
| `addEntry(string $playerName, string $text): bool` | Adds a new entry (respects `max-entries`) |
| `removeEntry(string $playerName, int $index): bool` | Removes an entry by index |
| `clearEntries(string $playerName): void` | Deletes all entries for a player |

### `Loader`

| Method | Description |
|--------|-------------|
| `getDataManager(): DataManager` | Returns the shared data manager instance |

---

## 🤝 Contributing

Contributions, issues, and feature requests are always welcome!

1. Fork the repository 🍴
2. Create your feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'Add some amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a Pull Request 🚀

---

## 🐛 Bug Reports

Found a bug? Please open an [issue](https://github.com/JustRohams/Binder/issues) with:
- Your PocketMine-MP version
- Steps to reproduce
- Console error logs (if any)

---

## 📜 License

This project is licensed under the **MIT License** — see the [LICENSE](LICENSE) file for details.

---

## 👤 Author

**RohaM**

- 🌐 GitHub: [@JustRohams](https://github.com/JustRohams)

---

<p align="center">
  Made with ❤️ for the PocketMine community
  <br>
  <sub>⭐ If you like this plugin, don't forget to star the repo!</sub>
</p>