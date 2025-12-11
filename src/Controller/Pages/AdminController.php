<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Bots\BotAdministrationService;
use App\Application\Bots\BotType;
use App\Application\HAL\LinkPopulator;
use App\Controller\Pages\Admin\BotCommandFormData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class AdminController extends AbstractController
{
    public function __construct(
        private readonly BotAdministrationService $botAdminService,
        private readonly LinkPopulator $linkPopulator,
    ) {
    }

    #[Route('/admin')]
    public function index(): Response
    {
        $bots = $this->botAdminService->list();

        return $this->render('admin/index.html.twig', [
            'bots' => $this->linkPopulator->populateWebLinks($bots),
        ]);
    }

    #[Route('/admin/bots', methods: 'POST')]
    public function createBot(
        #[MapRequestPayload] BotCommandFormData $createBotRequest,
    ): Response {
        $this->botAdminService->create($createBotRequest->toDto());

        return $this->index();
    }

    #[Route('/admin/bots/{botId}', methods: 'DELETE')]
    public function deleteBot(Uuid $botId): Response
    {
        $this->botAdminService->delete($botId);

        return $this->index();
    }

    #[Route('/admin/bots/_create/{type}', methods: 'GET')]
    public function getBotForm(string $type): Response
    {
        return match (BotType::{$type}) {
            BotType::Consumer => $this->render('admin/consumer_args.html.twig'),
            BotType::Producer => $this->render('admin/producer_args.html.twig'),
            _ => new Response(status: Response::HTTP_BAD_REQUEST)
        };
    }
}
