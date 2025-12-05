<?php

declare(strict_types=1);

namespace App\Controller\Pages;

use App\Application\Bots\BotAdministrationService;
use App\Controller\Pages\Admin\BotCommandFormData;
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
        #[MapRequestPayload] BotCommandFormData $createBotRequest,
    ): Response {
        $this->botAdminService->create($createBotRequest->toDto());

        return $this->index();
    }
}
