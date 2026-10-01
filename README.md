# 🎮 PlayerStatus

> A lightweight **PocketMine-MP** plugin that displays detailed player information — such as device, control mode, and ping — both via the `/player` command and through **ScoreHud** scoreboard tags.

<p align="center">
  <img src="https://img.shields.io/badge/PocketMine--MP-5.0.0+-blue?style=for-the-badge" alt="PocketMine-MP">
  <img src="https://img.shields.io/badge/version-1.0.0-green?style=for-the-badge" alt="Version">
  <img src="https://img.shields.io/badge/license-MIT-orange?style=for-the-badge" alt="License">
  <img src="https://img.shields.io/badge/author-RohaM-purple?style=for-the-badge" alt="Author">
  <img src="https://img.shields.io/badge/ScoreHud-Supported-yellow?style=for-the-badge" alt="ScoreHud">
</p>

---

## ✨ Features

- 🕹️ **Device Detection** — Shows the player's platform (Android, iOS, Windows, Xbox, etc.).
- 🎛️ **Control Mode** — Detects how the player is playing (Touch, Keyboard, Controller).
- 📱 **Device Model** — Displays the player's exact device model.
- 📶 **Live Ping** — Real-time ping display in milliseconds.
- 🖥️ **ScoreHud Integration** — Exposes dynamic tags for use in scoreboards.
- ⚡ **Command Support** — Query any online player with a single command.
- 🎨 **Colorful Console Banner** — A clean ASCII art banner on startup.

---

## 📦 Requirements

| Requirement | Version |
|-------------|---------|
| **PocketMine-MP** | `5.0.0` or higher |
| **PHP** | `8.0+` |
| **ScoreHud** *(optional)* | Latest release of [ScoreHud](https://github.com/Ifera/ScoreHud) |

> 💡 *ScoreHud is optional — the `/player` command works without it, but tags require ScoreHud to be installed.*

---

## 🛠️ Installation

1. **Download** the latest release of `PlayerStatus.phar`.
2. Drop the `.phar` file into your server's `plugins/` directory.
3. *(Optional)* Install **[ScoreHud](https://github.com/Ifera/ScoreHud)** for scoreboard integration.
4. Restart your server.
5. You're all set! ✅

---

## 🎮 Commands & Permissions

### Commands

| Command | Description |
|---------|-------------|
| `/player <name>` | Shows detailed status info about the specified player |

### Permissions

| Permission | Description | Default |
|------------|-------------|---------|
| `playerstatus.command` | Allows usage of the `/player` command | `true` |

---

## 📊 ScoreHud Tags

Once **ScoreHud** is installed, you can use these tags in your scoreboard configuration:

| Tag | Description | Example Output |
|-----|-------------|----------------|
| `{playerstatus.device}` | Player's operating system | `Android`, `iOS`, `Windows 10` |
| `{playerstatus.control}` | Player's current input mode | `Touch`, `Mouse & Keyboard`, `Controller` |
| `{playerstatus.devicemodel}` | Player's device model | `Samsung Galaxy S21` |

### Example ScoreHud Line

```yaml
lines:
  - "&bDevice: &f{playerstatus.device}"
  - "&bControl: &f{playerstatus.control}"
  - "&bModel: &f{playerstatus.devicemodel}"
```

---

## 🧭 How It Works

```
┌─────────────────────┐
│  /player <name>     │──▶  Fetches player info  ──▶  Sends formatted message
└─────────────────────┘
┌─────────────────────┐
│   ScoreHud Tags     │──▶  TagsResolveEvent      ──▶  Injects live values
└─────────────────────┘
```

---

## 📁 Project Structure

```
PlayerStatus/
├── plugin.yml
└── src/
    └── RohaM/
        └── PlayerStatus/
            ├── Loader.php
            └── ScorehudEvent.php
```

---

## 🧩 Device OS Reference

| ID | Platform |
|----|----------|
| 0 | Unknown |
| 1 | Android |
| 2 | iOS |
| 3 | macOS |
| 4 | FireOS |
| 5 | GearVR |
| 6 | HoloLens |
| 7 | Windows 10 |
| 8 | Windows |
| 9 | Dedicated |
| 10 | Orbis |
| 11 | PlayStation 4 |
| 12 | Nintendo Switch |
| 13 | Xbox One |

## 🎛️ Control Mode Reference

| ID | Control |
|----|---------|
| 0 | Unknown |
| 1 | Mouse & Keyboard |
| 2 | Touch |
| 3 | Controller |

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

Found a bug? Please open an [issue](https://github.com/JustRohams/PlayerStatus/issues) with:
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