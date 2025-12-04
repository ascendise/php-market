<?php

namespace App\Controller\Pages;

use App\Application\Bots\BotAdministrationService;
use App\Application\Bots\BotCommandDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class AdminController extends AbstractController
{
    public function __construct(private readonly BotAdministrationService $botAdminService)
    {
    }

    #[Route('/admin')]
    public function index(): Response
    {
        $bots = $this->botAdminService->list();

        return $this->render('admin/index.html.twig', [
            'bots' => $bots,
        ]);
    }

    #[Route('/admin/_create-bot', methods: 'POST')]
    public function createBot(
        #[MapRequestPayload] BotCommandDto $createBotRequest,
    ): Response {
        $this->botAdminService->create($createBotRequest);

        return $this->index();
    }
}
