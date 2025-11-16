<?php

namespace App\DataFixtures;

use App\Entity\Market\Item;
use App\Entity\Market\Offer;
use App\Entity\Market\Trader;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DevFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $bot = new User();
        $bot->setEmail('bot@ascendise.ch');
        $bot->setPassword($this->hasher->hashPassword($bot, 'bot'));
        $trader = new Trader();
        $trader->setBalance(1000);
        $bot->setTrader($trader);
        $manager->persist($trader);
        $manager->persist($bot);
        $item1 = new Item();
        $item1->setProductName('Tarcola');
        $item1->setQuantity(5);
        $manager->persist($item1);
        $trader->addInventory($item1);
        $item2 = new Item();
        $item2->setProductName('AK-47');
        $item2->setQuantity(2);
        $manager->persist($item2);
        $trader->addInventory($item2);
        $item3 = new Item();
        $item3->setProductName('7.62x39 FMJ');
        $item3->setQuantity(200);
        $manager->persist($item3);
        $trader->addInventory($item3);

        $offer1 = new Offer();
        $offer1->setProductName('SIG P223');
        $offer1->setQuantity(1);
        $offer1->setTotalPrice(199);
        $offer1->setSellerId($trader->getId());
        $manager->persist($offer1);
        $offer2 = new Offer();
        $offer2->setProductName('Gameboy Color');
        $offer2->setQuantity(3);
        $offer2->setTotalPrice(150);
        $offer2->setSellerId($trader->getId());
        $manager->persist($offer2);
        $offer3 = new Offer();
        $offer3->setProductName('Beer');
        $offer3->setQuantity(12);
        $offer3->setTotalPrice(24);
        $offer3->setSellerId($trader->getId());
        $manager->persist($offer3);

        $manager->flush();
    }
}
