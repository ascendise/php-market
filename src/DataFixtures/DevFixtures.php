<?php

namespace App\DataFixtures;

use App\Domain\Bots\Consumer;
use App\Domain\Bots\ConsumerArgs;
use App\Domain\Bots\ConsumeRate;
use App\Domain\Bots\Producer;
use App\Domain\Bots\ProducerArgs;
use App\Domain\Bots\ProduceRate;
use App\Domain\Bots\Range;
use App\Domain\Market\Product;
use App\Entity\BotBlueprint;
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
        $this->seedTradersWithOffers($manager);
        $this->seedBotBlueprints($manager);

        $manager->flush();
    }

    private function seedTradersWithOffers(ObjectManager $manager): void
    {
        // Normal User
        $traderUser = new User();
        $traderUser->setEmail('trader@ascendise.ch');
        $traderUser->setPassword($this->hasher->hashPassword($traderUser, 'trader'));
        $trader = new Trader();
        $trader->setBalance(1000);
        $traderUser->setTrader($trader);
        $manager->persist($trader);
        $manager->persist($traderUser);
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

        // Admin user
        $admin = new User();
        $admin->setEmail('admin@ascendise.ch');
        $admin->setPassword($this->hasher->hashPassword($admin, 'admin'));
        $admin->setRoles(['ROLE_ADMIN']);
        $adminTrader = new Trader();
        $adminTrader->setBalance(999999);
        $admin->setTrader($adminTrader);
        $manager->persist($adminTrader);
        $manager->persist($admin);

        // Initial offers
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
    }

    private function seedBotBlueprints(ObjectManager $manager): void
    {
        $producerArgs = new ProducerArgs([
            new ProduceRate(
                new Product('Apple'),
                tradingVolume: new Range(80, 100),
                offerQuantity: new Range(5, 20),
                pricePerItem: new Range(1, 3)
            ),
        ]);
        $producer = new BotBlueprint()
            ->setType(Producer::class)
            ->setArgs($producerArgs)
            ->setFrequency(\DateInterval::createFromDateString('3 seconds'));
        $manager->persist($producer);
        $consumerArgs = new ConsumerArgs([
            new ConsumeRate(
                new Product('Apple'),
                budget: new Range(100, 300),
                buyingVolume: new Range(40, 120)
            ),
        ]);
        $consumer = new BotBlueprint()
            ->setType(Consumer::class)
            ->setArgs($consumerArgs)
            ->setFrequency(\DateInterval::createFromDateString('5 seconds'));
        $manager->persist($consumer);
    }
}
