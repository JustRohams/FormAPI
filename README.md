# GiftCode

A simple and optimized gift code plugin for **PocketMine-MP API 5** powered by **EconomyAPI**.

## ✨ Features

- Create gift codes with custom amounts of money
- Redeem codes for all players
- Fully configurable messages
- Persistent storage (`gifts.yml`)
- Clean and optimized codebase
- Permission-based code creation

## 📦 Requirements

- [PocketMine-MP](https://github.com/pmmp/PocketMine-MP) `API 5.0.0+`
- [EconomyAPI](https://github.com/onebone/EconomyS/tree/3.x/EconomyAPI) (PMMP 5 branch)

## 🔧 Installation

1. Download the plugin `.phar`.
2. Put it inside the `plugins/` folder of your server.
3. Restart the server.
4. Configure messages in `plugin_data/GiftCode/config.yml` if needed.

## 🎮 Commands

| Command | Description | Permission |
|---------|-------------|------------|
| `/giftcreate <amount> <code>` | Create a gift code worth `<amount>` money | `giftcode.create` |
| `/gift <code>` | Redeem a gift code | `giftcode.use` (everyone) |

Aliases: `/gcreate` for `/giftcreate`

### Example

`/giftcreate 100000 TEST`

Creates a gift code `TEST` worth `100000` money.

`/gift TEST`

Any player who runs this receives `100000` money and the code is consumed.

## 🔐 Permissions

| Permission | Default | Description |
|------------|---------|-------------|
| `giftcode.create` | `op` | Allows creating gift codes |
| `giftcode.use` | `true` | Allows redeeming gift codes |

## ⚙️ Configuration

Messages are stored inside `plugin_data/GiftCode/config.yml`.
Supported placeholders: `{code}`, `{amount}`.

## 👤 Author

**- [RohaM](https://github.com/JustRohams)**

## 📄 License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.