# FormAPI — Persian Fork

A drop-in fork of [Jojoe77777's FormAPI](https://github.com/jojoe77777/FormAPI) with **automatic Persian (Farsi) text shaping and right-to-left support** for PocketMine-MP 5.

Bedrock has no Arabic-script shaping and no RTL support, so Persian text sent from a plugin shows up as disconnected letters in reverse order. This fork embeds a lightweight Persian text engine (a slim copy of [`libPersianText`](https://github.com/ApexMine/libPersianText) by Kevin) inside `FormAPI\lib` and **fixes every string automatically** the moment the form is serialized — titles, content, buttons, labels, dropdown options, input placeholders, step sliders, everything.

```php
use jojoe77777\FormAPI\SimpleForm;

$form = new SimpleForm(function($player, $data){ /* ... */ });
$form->setTitle("§aفروشگاه");          // ← gets shaped and RTL-fixed automatically
$form->setContent("§eموجودی شما: §f100"); // ← and so does every button / field
$form->addButton("§aخرید");
$form->addButton("§cانصراف");
$player->sendForm($form);
```

You write Persian naturally, the fork handles the rest.

---

## Features

- **Zero-effort Persian support.** No need to call `Persian::fix()` yourself anywhere — the base `Form` class applies it to all strings in the form data on `jsonSerialize()`.
- **Works on every form type.** `SimpleForm`, `CustomForm`, `ModalForm` — including all custom-form widgets (`label`, `toggle`, `slider`, `step_slider`, `dropdown`, `input`).
- **Safe for English/non-Persian.** Any string without Arabic-script characters is returned unchanged (fast path + LRU-style cache up to 1024 entries).
- **`§` color codes, English words, numbers and commands are preserved.** Latin runs (`/shop buy`) stay together; bracket groups `()`, `[]`, `{}`, `<>` are reversed as a single block.
- **Global toggle.** `Persian::setEnabled(false)` disables the whole feature for Latin-only servers.
- **Minimal diff from upstream.** `SimpleForm`, `CustomForm`, and `ModalForm` are untouched — only `Form.php` gains one private helper.

## Requirements

- PocketMine-MP **5.x**
- PHP **8.1+** with `ext-mbstring` (both ship with PocketMine-MP 5)

## Installation

### As a plugin

1. Download `FormAPI.phar` from [Releases](../../releases) and drop it into your server's `plugins/` folder (or use [DevTools](https://poggit.pmmp.io/p/DevTools) with the source folder).
2. Restart the server.

### As a virion

Put this repository into your plugin's `virions/` folder with [DEVirion](https://poggit.pmmp.io/p/DEVirion) and inject it at build time. The antigen is `jojoe77777\FormAPI`.

### With Composer

```json
{
    "repositories": [{ "type": "vcs", "url": "https://github.com/RohaM/FormAPI" }],
    "require": { "roham/formapi": "^2.0" }
}
```

## Usage

```php
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\lib\Persian;
use pocketmine\player\Player;

// SimpleForm — buttons + content
$form = new SimpleForm(function (Player $player, ?int $data) : void {
    if ($data === null) return;
    $player->sendMessage("Selected: $data");
});
$form->setTitle("§6منوی اصلی");
$form->setContent("§eیک گزینه را انتخاب کنید:");
$form->addButton("§aخرید");
$form->addButton("§cفروش");
$form->addButton("§bراهنما");
$player->sendForm($form);

// CustomForm — inputs, sliders, dropdowns
$form = new CustomForm(function (Player $player, ?array $data) : void {
    if ($data === null) return;
    $player->sendMessage("نام: " . $data["name"]);
});
$form->setTitle("§6ثبت‌نام");
$form->addInput("§eنام شما:", "نام را وارد کنید", "", "name");
$form->addSlider("§eسن:", 10, 60, 1, 20);
$form->addDropdown("§eشهر:", ["تهران", "مشهد", "اصفهان"]);
$player->sendForm($form);
```

### Disabling Persian processing

```php
use jojoe77777\FormAPI\lib\Persian;

Persian::setEnabled(false); // returns every string untouched, useful for English-only servers
```

### Long lines

If a Persian line is too long, the client wraps it itself and the pieces appear bottom-to-top. To split at word boundaries before shaping:

```php
// The base Form class exposes fix() through the same engine — use it directly if you need control:
use jojoe77777\FormAPI\lib\Persian;

$form->setContent(Persian::fix($longText, 40)); // ~40 suits forms
```

> ⚠️ Only do this if you need a specific `$maxLineLength`. Otherwise let the form fix it automatically — calling `fix()` **twice** on the same string reverses it twice.

## What is fixed automatically

Every string in the form's JSON payload:

- `title`, `content` (both `SimpleForm`/`ModalForm` strings and every element of `CustomForm`'s content array)
- `text`, `placeholder`, `default` (when string), `options[]`, `steps[]`
- `button1`, `button2`
- Button `text` and any label

Non-string values (ints, bools) are left alone. Keys (`type`, `min`, `max`, `image`, etc.) are never touched.

## Tips

- **Fix once, at the end.** Since the form applies `fix()` for you, do **not** pre-fix your strings — otherwise you'll get reversed text.
- **First word of a line is drawn on the right.** To show a command on the left with a description on the right, write the description first: `"باز کردن فروشگاه : /shop"`.
- **Don't wrap English arguments in `<>`.** `"/shop <item>"` displays as `<item> /shop`. Use `"/shop ItemName"` instead.
- **ZWNJ (نیم‌فاصله)** is fine in source code — the engine consumes it during shaping and removes it before display.
- **Use English digits** and write «درصد» instead of `٪` — some fonts lack the symbol.
- **ModalForm** on servers whose resource pack doesn't style modals can show Persian as boxes. Use `SimpleForm` with two buttons if that happens.

## File structure

```
src/jojoe77777/FormAPI/
├── FormAPI.php
├── Form.php               ← only modified file (adds Persian pass in jsonSerialize)
├── SimpleForm.php
├── CustomForm.php
├── ModalForm.php
└── lib/                   ← embedded Persian text engine
    ├── Persian.php
    └── PersianTextEngine.php
```

## Credits

- **Original FormAPI:** [Jojoe77777](https://github.com/jojoe77777)
- **Fork / Persian integration:** [RohaM](https://github.com/RohaM)
- **Persian text engine:** Kevin ([libPersianText](https://github.com/ApexMine/libPersianText))

## License

Released under the [MIT License](LICENSE).
