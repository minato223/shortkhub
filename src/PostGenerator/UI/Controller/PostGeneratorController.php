<?php

namespace App\PostGenerator\UI\Controller;

use App\Infrastructure\Mercure\MercureService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

class PostGeneratorController extends AbstractController
{

    public function __construct() {}

    #[Route('/post-generator', name: 'post_generator', methods: 'GET')]
    public function index()
    {
        try {
            $list = [];
        } catch (\Throwable $th) {
            $list = [];
        }
        return $this->render('post_generator.html.twig', compact('list'));
    }

    #[Route('/post-generator/generate', name: 'post_generator_generate', methods: 'POST')]
    public function generate(
        // #[MapRequestPayload] ScreenshotQuery $query
    ): JsonResponse
    {
        $response = [];
        try {
            return new JsonResponse($response);
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], 500);
        }
    }

    #[Route('/post-generator/notifier', name: 'post_generator_notifier', methods: 'POST')]
    public function notifier(
        Request $request,
        MercureService $mercureService
    ): JsonResponse {
        $json = $request->getContent();
        $mercureService->sendMessage($json, "/progress");
        return new JsonResponse();
    }
}
