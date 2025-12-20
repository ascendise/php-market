<?php

declare(strict_types=1);

namespace App\Application\Market;

use App\Application\Auth\UserRepository;
use App\Application\Events\EventDto;
use App\Application\Events\Transform;
use App\Domain\Market\BalanceChangedEvent;
use Twig\Environment;

final class BalanceChangedEventTransformHtml extends Transform
{
    public function __construct(
        private readonly Environment $twig,
        private readonly UserRepository $userRepo,
    ) {
        parent::__construct(BalanceChangedEvent::class);
    }

    protected function transformEvent(mixed $event): EventDto
    {
        $event = BalanceChangedEventDto::fromEntity($event);
        $view = $this->twig->render('market/_trader_balance.html.twig', ['balance' => $event->newBalance]);
        $view = str_replace('\n', '', $view);
        $user = $this->userRepo->fetchFromTrader($event->trader);

        return new EventDto(BalanceChangedEvent::class, 'html', $view, $user->getEmail());
    }
}
