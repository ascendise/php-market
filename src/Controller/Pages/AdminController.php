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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Uid\Uuid;

#[IsGranted('ROLE_ADMIN')]
final class AdminController extends AbstractController
{
    public function __construct(
        private readonly BotAdministrationService $botAdminService,
        private readonly LinkPopulator $linkPopulator,
    ) {
    }

    #[Route('/admin', methods: 'GET')]
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

    #[Route('/admin/bots/{botId}', methods: 'GET')]
    public function getBot(Uuid $botId): Response
    {
        $bot = $this->botAdminService->findById($botId);
        if (!$bot) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        return $this->render('admin/_update_bot.html.twig', [
            'bot' => $this->linkPopulator->populateWebLinks($bot),
        ]);
    }

    #[Route('/admin/bots/_args-editor/{type}', methods: 'GET')]
    public function getBotForm(string $type): Response
    {
        return match (BotType::{$type}) {
            BotType::Consumer => $this->render('admin/_consumer_args.html.twig'),
            BotType::Producer => $this->render('admin/_producer_args.html.twig'),
            default => new Response(status: Response::HTTP_BAD_REQUEST)
        };
    }

    #[Route('/admin/bots/{botId}', methods: 'PUT')]
    public function updateBot(Uuid $botId, #[MapRequestPayload] BotCommandFormData $updateBotRequest): Response
    {
        $bot = $this->botAdminService->update($botId, $updateBotRequest->toDto());
        if (!$bot) {
            return new Response(status: Response::HTTP_NOT_FOUND);
        }

        return $this->index();
    }

    #[Route('/admin/bots/{botId}', methods: 'DELETE')]
    public function deleteBot(Uuid $botId): Response
    {
        $this->botAdminService->delete($botId);

        return $this->index();
    }
}
