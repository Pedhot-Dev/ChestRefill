<?php


namespace pedhot\ChestRefill;


use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\tile\Chest;

class Main extends PluginBase
{

    public function onEnable()
    {
        Server::getInstance()->getCommandMap()->register($this->getDescription()->getName(), new Cmd("refill", $this));
    }

    public function refillChest(Player $player, string $worldName)
    {
        $contents = $this->getChestContents();
        foreach (Server::getInstance()->getLevelByName($worldName)->getTiles() as $tile){
            if ($tile instanceof Chest){
                $inv = $tile->getInventory();
                $inv->clearAll(false);

                if (empty($contents)){
                    $contents = $this->getChestContents();
                }

                foreach (array_shift($contents) as $key => $val) {
                    $inv->setItem($key, Item::get($val[0], 0, $val[1]), false);
                }

                $inv->sendContents($inv->getViewers());
            }
        }
    }

    public function getChestContents() : array
    {
        $items = [
            //ARMOR
            "armor" => [
                [
                    Item::LEATHER_CAP,
                    Item::LEATHER_TUNIC,
                    Item::LEATHER_PANTS,
                    Item::LEATHER_BOOTS
                ],
                [
                    Item::GOLD_HELMET,
                    Item::GOLD_CHESTPLATE,
                    Item::GOLD_LEGGINGS,
                    Item::GOLD_BOOTS
                ],
                [
                    Item::CHAIN_HELMET,
                    Item::CHAIN_CHESTPLATE,
                    Item::CHAIN_LEGGINGS,
                    Item::CHAIN_BOOTS
                ],
                [
                    Item::IRON_HELMET,
                    Item::IRON_CHESTPLATE,
                    Item::IRON_LEGGINGS,
                    Item::IRON_BOOTS
                ],
                [
                    Item::DIAMOND_HELMET,
                    Item::DIAMOND_CHESTPLATE,
                    Item::DIAMOND_LEGGINGS,
                    Item::DIAMOND_BOOTS
                ]
            ],

            //WEAPONS
            "weapon" => [
                [
                    Item::WOODEN_SWORD,
                    Item::WOODEN_AXE,
                ],
                [
                    Item::GOLD_SWORD,
                    Item::GOLD_AXE
                ],
                [
                    Item::STONE_SWORD,
                    Item::STONE_AXE
                ],
                [
                    Item::IRON_SWORD,
                    Item::IRON_AXE
                ],
                [
                    Item::DIAMOND_SWORD,
                    Item::DIAMOND_AXE
                ]
            ],

            //FOOD
            "food" => [
                [
                    Item::RAW_PORKCHOP,
                    Item::RAW_CHICKEN,
                    Item::MELON_SLICE,
                    Item::COOKIE
                ],
                [
                    Item::RAW_BEEF,
                    Item::CARROT
                ],
                [
                    Item::APPLE,
                    Item::GOLDEN_APPLE
                ],
                [
                    Item::BEETROOT_SOUP,
                    Item::BREAD,
                    Item::BAKED_POTATO
                ],
                [
                    Item::MUSHROOM_STEW,
                    Item::COOKED_CHICKEN
                ],
                [
                    Item::COOKED_PORKCHOP,
                    Item::STEAK,
                    Item::PUMPKIN_PIE
                ],
            ],

            //THROWABLE
            "throwable" => [
                [
                    Item::BOW,
                    Item::ARROW
                ],
                [
                    Item::SNOWBALL
                ],
                [
                    Item::EGG
                ]
            ],

            //BLOCKS
            "block" => [
                Item::STONE,
                Item::WOODEN_PLANKS,
                Item::COBBLESTONE,
                Item::DIRT
            ],

            //OTHER
            "other" => [
                [
                    Item::WOODEN_PICKAXE,
                    Item::GOLD_PICKAXE,
                    Item::STONE_PICKAXE,
                    Item::IRON_PICKAXE,
                    Item::DIAMOND_PICKAXE
                ],
                [
                    Item::STICK,
                    Item::STRING
                ]
            ]
        ];

        $templates = [];
        for ($i = 0; $i < 10; $i++) {//TODO: understand wtf is the stuff in here doing

            $armorq = mt_rand(0, 1);
            $armortype = $items["armor"][array_rand($items["armor"])];

            $armor1 = [$armortype[array_rand($armortype)], 1];
            if ($armorq) {
                $armortype = $items["armor"][array_rand($items["armor"])];
                $armor2 = [$armortype[array_rand($armortype)], 1];
            } else {
                $armor2 = [0, 1];
            }

            $weapontype = $items["weapon"][array_rand($items["weapon"])];
            $weapon = [$weapontype[array_rand($weapontype)], 1];

            $ftype = $items["food"][array_rand($items["food"])];
            $food = [$ftype[array_rand($ftype)], mt_rand(2, 5)];

            if (mt_rand(0, 1)) {
                $tr = $items["throwable"][array_rand($items["throwable"])];
                if (count($tr) === 2) {
                    $throwable1 = [$tr[1], mt_rand(10, 20)];
                    $throwable2 = [$tr[0], 1];
                } else {
                    $throwable1 = [0, 1];
                    $throwable2 = [$tr[0], mt_rand(5, 10)];
                }
                $other = [0, 1];
            } else {
                $throwable1 = [0, 1];
                $throwable2 = [0, 1];
                $ot = $items["other"][array_rand($items["other"])];
                $other = [$ot[array_rand($ot)], 1];
            }

            $block = [$items["block"][array_rand($items["block"])], 64];

            $contents = [
                $armor1,
                $armor2,
                $weapon,
                $food,
                $throwable1,
                $throwable2,
                $block,
                $other
            ];
            shuffle($contents);

            $fcontents = [
                mt_rand(0, 1) => array_shift($contents),
                mt_rand(2, 4) => array_shift($contents),
                mt_rand(5, 9) => array_shift($contents),
                mt_rand(10, 14) => array_shift($contents),
                mt_rand(15, 16) => array_shift($contents),
                mt_rand(17, 19) => array_shift($contents),
                mt_rand(20, 24) => array_shift($contents),
                mt_rand(25, 26) => array_shift($contents),
            ];

            $templates[] = $fcontents;
        }

        shuffle($templates);
        return $templates;
    }

}