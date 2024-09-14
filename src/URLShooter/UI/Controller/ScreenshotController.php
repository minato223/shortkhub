<?php

namespace App\URLShooter\UI\Controller;

use App\URLShooter\Application\GenerateScreenshotUseCase;
use App\URLShooter\Application\GetAllScreenshotsUseCase;
use App\URLShooter\Domain\Entity\ScreenshotQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class ScreenshotController extends AbstractController {

    public function __construct(
        private GenerateScreenshotUseCase $generateScreenshotUseCase,
        private GetAllScreenshotsUseCase $getAllScreenshotsUseCase
    ) {}

    #[Route('/screenshot', name: 'screenshot')]
    public function index() {
        try {
            $list = $this->getAllScreenshotsUseCase->execute();
        } catch (\Throwable $th) {
            $list = [];
        }
        return $this->render('screenshot.html.twig', compact('list'));
    }

    #[Route('/screenshot/generate', name: 'screenshot_generate', methods: 'POST')]
    public function generate(
        #[MapRequestPayload] ScreenshotQuery $query
    ): JsonResponse {
        $response = $this->generateScreenshotUseCase->execute($query);
        try {
            return new JsonResponse($response);
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], 500);
        }
    }
}