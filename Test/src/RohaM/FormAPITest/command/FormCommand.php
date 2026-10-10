<?php

declare(strict_types = 1);

namespace RohaM\FormAPITest\command;

use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\lib\Persian;
use jojoe77777\FormAPI\lib\PersianTextEngine;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use RohaM\FormAPITest\FormAPITest;

final class FormCommand extends Command implements PluginOwned{

    private FormAPITest $plugin;

    public function __construct(FormAPITest $plugin){
        parent::__construct(
            "formtest",
            "Test FormAPI Persian support",
            "/formtest <simple|custom|modal|kitchen|toggle|debug>",
            ["ftest"]
        );
        $this->plugin = $plugin;
        $this->setPermission("formapitest.use");
    }

    public function getOwningPlugin() : Plugin{
        return $this->plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : void{
        if(!$sender instanceof Player){
            $sender->sendMessage("§cThis command can only be used in-game.");
            return;
        }

        $mode = strtolower($args[0] ?? "simple");

        match($mode){
            "simple"  => $this->openSimpleForm($sender),
            "custom"  => $this->openCustomForm($sender),
            "modal"   => $this->openModalForm($sender),
            "kitchen" => $this->openKitchenSinkForm($sender),
            "toggle"  => $this->togglePersian($sender),
            "debug"   => $this->runDebug($sender),
            default   => $sender->sendMessage("§eUsage: §f/formtest <simple|custom|modal|kitchen|toggle|debug>")
        };
    }

    private function runDebug(Player $player) : void{
        $player->sendMessage("§6===== FormAPI Persian Debug =====");

        $engineOut = PersianTextEngine::process("سلام");
        $player->sendMessage("§e1) Engine §fprocess(\"سلام\")");
        $player->sendMessage("   §7result: §f{$engineOut}");
        $player->sendMessage("   §7bytes:  §f" . bin2hex($engineOut));
        $player->sendMessage("   §7status: " . ($engineOut !== "سلام" ? "§a working" : "§c NOT working (returns input)"));

        $fixOut = Persian::fix("سلام");
        $player->sendMessage("§e2) Persian §ffix(\"سلام\")");
        $player->sendMessage("   §7result: §f{$fixOut}");
        $player->sendMessage("   §7status: " . ($fixOut !== "سلام" ? "§aworking" : "§c NOT working"));

        $player->sendMessage("§e3) Persian enabled: §f" . (Persian::isEnabled() ? "§atrue" : "§cfalse"));

        $form = new SimpleForm(null);
        $form->setTitle("سلام");
        $serialized = $form->jsonSerialize();
        $titleAfter = $serialized["title"] ?? "(missing)";
        $player->sendMessage("§e4) SimpleForm::setTitle(\"سلام\")");
        $player->sendMessage("   §7title in data: §f{$titleAfter}");
        $player->sendMessage("   §7status: " . ($titleAfter !== "سلام" ? "§afixed at setter" : "§c NOT fixed"));

        if($engineOut === "سلام"){
            $player->sendMessage("§c PersianTextEngine is corrupted! The glyph table is empty or wrong.");
            $player->sendMessage("§7Replace lib/PersianTextEngine.php with the \\u-escaped version.");
        } elseif($titleAfter === "سلام"){
            $player->sendMessage("§c Form.php / SimpleForm.php not applying fix in setters.");
            $player->sendMessage("§7Replace Form.php, SimpleForm.php, CustomForm.php, ModalForm.php.");
        } else {
            $player->sendMessage("§a✅ Everything looks correct!");
        }
    }

    private function togglePersian(Player $player) : void{
        if(!$player->hasPermission("formapitest.admin")){
            $player->sendMessage("§cYou don't have permission to toggle Persian.");
            return;
        }

        $new = !Persian::isEnabled();
        Persian::setEnabled($new);
        $player->sendMessage("§6Persian engine is now: " . ($new ? "§aON" : "§cOFF"));
    }

    private function openSimpleForm(Player $player) : void{
        $form = new SimpleForm(function(Player $player, ?int $data) : void{
            if($data === null){
                $player->sendMessage("§7فرم بسته شد. خداحافظ!");
                return;
            }

            $messages = [
                "§aشما §eخرید §a را انتخاب کردید.",
                "§aشما §cفروش §a را انتخاب کردید.",
                "§aشما §bراهنما §a را انتخاب کردید.",
                "§aشما §dتنظیمات §a را انتخاب کردید."
            ];

            $player->sendMessage($messages[$data] ?? "§cگزینه نامعتبر!");
        });

        $form->setTitle("§6منوی اصلی سرور");
        $form->setContent(
            "§eسلام §f{$player->getName()}§e!\n" .
            "§7به سرور ما خوش آمدید.\n" .
            "§aیک گزینه را از پایین انتخاب کنید:"
        );

        $form->addButton("§aخرید", SimpleForm::IMAGE_TYPE_PATH, "textures/items/gold_ingot");
        $form->addButton("§cفروش", SimpleForm::IMAGE_TYPE_PATH, "textures/items/emerald");
        $form->addButton("§bراهنما");
        $form->addButton("§dتنظیمات");

        $player->sendForm($form);
    }

    private function openCustomForm(Player $player) : void{
        $form = new CustomForm(function(Player $player, ?array $data) : void{
            if($data === null){
                $player->sendMessage("§7ثبت‌نام لغو شد.");
                return;
            }

            $player->sendMessage("§a=== نتیجه ثبت‌نام ===");
            $player->sendMessage("§eنام: §f" . $data["name"]);
            $player->sendMessage("§eسن: §f" . $data["age"]);
            $player->sendMessage("§eشهر: §f" . $data["city"]);
            $player->sendMessage("§eسطح مهارت: §f" . $data["level"]);
            $player->sendMessage("§eخبرنامه: §f" . ($data["news"] ? "بله" : "خیر"));
            $player->sendMessage("§eتوضیح: §f" . ($data["bio"] !== "" ? $data["bio"] : "(خالی)"));
        });

        $form->setTitle("§6فرم ثبت‌نام");
        $form->addLabel("§7لطفاً اطلاعات زیر را با دقت پر کنید.");
        $form->addInput("§eنام شما:", "مثلاً: علی", "", "name");
        $form->addSlider("§eسن:", 10, 60, 1, 20, "age");
        $form->addDropdown(
            "§eشهر:",
            ["تهران", "مشهد", "اصفهان", "شیراز", "تبریز"],
            0,
            "city"
        );
        $form->addStepSlider(
            "§eسطح مهارت:",
            ["مبتدی", "متوسط", "حرفه‌ای", "استاد"],
            1,
            "level"
        );
        $form->addToggle("§eعضویت در خبرنامه:", true, "news");
        $form->addInput("§eدرباره شما:", "چند خط درباره خودت بنویس...", "", "bio");

        $player->sendForm($form);
    }

    private function openModalForm(Player $player) : void{
        $form = new ModalForm(function(Player $player, ?bool $data) : void{
            if($data === null){
                $player->sendMessage("§7هیچ پاسخی داده نشد.");
                return;
            }
            if($data){
                $player->sendMessage("§aعالی! تأیید کردید.");
                $player->sendMessage("§7در حال اجرای عملیات...");
            }else{
                $player->sendMessage("§cلغو شد.");
            }
        });

        $form->setTitle("§cتأیید حذف");
        $form->setContent(
            "§eآیا مطمئن هستید که می‌خواهید §cاین آیتم §eرا حذف کنید؟\n" .
            "§7این عمل قابل بازگشت نیست!"
        );
        $form->setButton1("§aبله، حذف کن");
        $form->setButton2("§cخیر، لغو کن");

        $player->sendForm($form);
    }

    private function openKitchenSinkForm(Player $player) : void{
        $form = new SimpleForm(function(Player $player, ?int $data) : void{
            if($data === null) return;

            $info = [
                "§7— تست خط بلند —",
                "§7— تست ترکیب فارسی و انگلیسی —",
                "§7— تست ZWNJ و نیم‌فاصله —",
                "§7— تست براکت‌ها و دستورها —",
                "§7— تست رنگ‌های مختلف —"
            ];

            $player->sendMessage($info[$data] ?? "§c?");
        });

        $form->setTitle("§b تست جامع متن فارسی");

        $form->setContent(
            "§6نمونه متن‌های مختلف】§r\n\n" .
            "§a جمله ساده:\n" .
            "§fسلام، به سرور ما خوش آمدی!\n\n" .
            "§a ترکیب فارسی و انگلیسی:\n" .
            "§fبرای راهنما بزن §e/help §fیا از §e/menu §fاستفاده کن.\n\n" .
            "§a نیم‌فاصله و ZWNJ:\n" .
            "§fمی‌شود، نمی‌شود، کتاب‌ها، آن‌ها، خوش‌آمدید.\n\n" .
            "§a براکت‌ها و پرانتز:\n" .
            "§f[فروشگاه] و (راهنما) و {تنظیمات} همه باز هستند.\n\n" .
            "§a اعداد و درصد:\n" .
            "§fموجودی: §e100 §f | سطح: §e35 §f | پیشرفت: §e87 درصد\n\n" .
            "§a لا و اشکال خاص:\n" .
            "§fالله، لا اله الا الله، ولی، علی، اسلامی."
        );

        $form->addButton("§eخط بلند و شکست کلمه");
        $form->addButton("§eترکیب فارسی + انگلیسی");
        $form->addButton("§eنیم‌فاصله و ZWNJ");
        $form->addButton("§eبراکت و دستورها");
        $form->addButton("§eرنگ‌های متنوع");

        $player->sendForm($form);
    }
}