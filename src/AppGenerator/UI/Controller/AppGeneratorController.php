<?php

namespace App\AppGenerator\UI\Controller;

use App\AppGenerator\Domain\Entity\ApkDocument;
use App\AppGenerator\Domain\Entity\AppVersion;
use App\AppGenerator\Domain\Entity\IconDocument;
use App\AppGenerator\Domain\Entity\Project;
use App\AppGenerator\Domain\Repository\ProjectRepository;
use App\AppGenerator\UI\Service\AppGeneratorService;
use App\Infrastructure\Mercure\MercureService;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AppGeneratorController extends AbstractController
{

    public function __construct(
        private readonly UrlGeneratorInterface $urlGeneratorInterface,
        private readonly RequestStack $requestStack
    ) {}

    #[Route('/app-generator', name: 'app_generator', methods: 'GET')]
    public function index(ProjectRepository $projectRepository, AppGeneratorService $appGeneratorService): Response
    {
        $list = $projectRepository->findAll();
        $list = array_map(function (Project $project) use ($appGeneratorService) {
            return $appGeneratorService->toArray($project);
        }, $list);
        return $this->render('app_generator.html.twig', compact('list'));
    }

    #[Route('/app-generator/create', name: 'app_generator_create', methods: 'POST')]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        EntityManagerInterface $entityManager,
        AppGeneratorService $appGeneratorService
    ): JsonResponse {
        $accessor = new PropertyAccessor();
        $json = $request->request->all();
        $name = $accessor->getValue($json, '[name]');
        $description = $accessor->getValue($json, '[description]');
        $url = $accessor->getValue($json, '[url]');
        $iconFile = $accessor->getValue($request->files->all(), '[icon]');
        $project = new Project();
        $project->setName($name);
        $project->setDescription($description);
        $project->setUrl($url);
        $project->setUuid(AppGeneratorService::toSnakeCase($name));
        $icon = new IconDocument();
        $icon->setFile($iconFile);
        $project->setIcon($icon);
        $violations = $validator->validate($project);
        if ($violations->count() > 0) {
            return new JsonResponse(['error' => $violations->get(0)->getMessage()], 500);
        }
        try {
            $entityManager->persist($project);
            $entityManager->flush();
            $entityManager->refresh($project);
            return new JsonResponse(
                ['message' => 'success', 'data' => $appGeneratorService->toArray($project)],
            );
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], 500);
        }
    }

    #[Route('/app-generator/generate', name: 'app_generator_generate', methods: 'POST')]
    public function generate(
        Request $request,
        ProjectRepository $projectRepository,
        MercureService $mercureService,
        UploaderHelper $uploaderHelper,
        LoggerInterface $logger
    ): JsonResponse {
        $accessor = new PropertyAccessor();
        $uuid = $accessor->getValue(json_decode($request->getContent(), true), '[uuid]');
        $project = $projectRepository->findOneByUuid($uuid);
        $path = $uploaderHelper->asset($project->getIcon());
        try {
            $this->sendRequest($project, $path);
            $mercureService->sendMessage("Hello", '/progress');
            $logger->info('Message sent to Mercure topic: /progress');
            return new JsonResponse(['message' => 'success', 'data' => "App Generation", 200]);
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], 500);
        }
    }

    #[Route('/app-generator/notifier', name: 'app_generator_notifier', methods: 'POST')]
    public function notifier(
        Request $request,
        MercureService $mercureService
    ): JsonResponse {
        $json = $request->getContent();
        $mercureService->sendMessage($json, sprintf("/%s", 'progress'));
        return new JsonResponse([
            'message' => 'success',
        ]);
    }

    #[Route('/app-generator/apk', name: 'app_generator_apk', methods: 'POST')]
    public function apk(
        Request $request,
        EntityManagerInterface $entityManager,
        MercureService $mercureService
    ): JsonResponse {
        try {
            $accessor = new PropertyAccessor();
            $json = $request->request->all();
            $id = $accessor->getValue($json, '[id]');
            $apk_file = $request->files->get('apk');
            $project = $entityManager->getRepository(Project::class)->findOneByUuid($id);
            $appVersion = new AppVersion();
            $appVersion->setProject($project);
            $appVersion->setType('apk');
            $appVersion->setPlatform('android');
            $apk = (new ApkDocument())->setFile($apk_file);
            $appVersion->setFile($apk);
            $entityManager->persist($appVersion);
            $entityManager->flush();
            $mercureService->sendMessage("Hello", sprintf("/%s", 'progress'));
            return new JsonResponse(
                ['message' => 'success', 'apk' => 'ok'],
            );
        } catch (\Throwable $th) {
            return new JsonResponse(['error' => $th->getMessage()], 500);
        }
    }

    #[Route('/app-generator/{uuid}', name: 'app_generator_detail', methods: 'GET')]
    public function detail(
        Request $request,
        AppGeneratorService $appGeneratorService,
        ProjectRepository $projectRepository
    ): Response {
        $accessor = new PropertyAccessor();
        $uuid = $accessor->getValue($request->attributes->all(), '[uuid]');
        $project = $projectRepository->findOneByUuid($uuid);
        return $this->render('app_generator_detail.html.twig', [
            'project' => $appGeneratorService->toArray($project)
        ]);
    }

    #[Route('/app-generator/{uuid}/download', name: 'file_download', methods: ['GET'])]
    public function download(
        Request $request,
        ProjectRepository $projectRepository,
        UploaderHelper $uploaderHelper,
    ): Response
    {
        $accessor = new PropertyAccessor();
        $uuid = $accessor->getValue($request->attributes->all(), '[uuid]');
        /** @var Project $project */
        $project = $projectRepository->findOneByUuid($uuid);
        $project->getVersions();
        /** @var AppVersion $lastVersion */
        $lastVersion = $project->getVersions()->last();
        if (!$lastVersion) {
            throw $this->createNotFoundException('No version found');
        }
        $path = $uploaderHelper->asset($lastVersion->getFile());
        $apkPath = $this->getParameter('kernel.project_dir') . '/public/' . $path;
        if (!file_exists($apkPath)) {
            throw $this->createNotFoundException('File not found');
        }

        return new Response(
            file_get_contents($apkPath),
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . basename($project->getUuid()) . '.apk"',
            ]
        );
    }

    private function sendRequest(Project $project, string $path): void
    {
        $httpClient = HttpClient::create();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/' . $path;
        $file = new File($imagePath);
        $response = $httpClient->request('POST', 'http://127.0.0.1:4000/generate', [
            'body' => [ 
                'name' => $project->getName(),
                'url' => $project->getUrl(),
                'notif_url' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $this->urlGeneratorInterface->generate('app_generator_notifier'),
                'app_post_url' => $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost() . $this->urlGeneratorInterface->generate('app_generator_apk'),
                'description' => $project->getDescription(),
                'id' => $project->getUuid(),
                // 'file'=> new UploadedFile($imagePath, basename($imagePath), null, null, false, true),
            ],
        ]);
        $statusCode = $response->getStatusCode();
        $content = $response->getContent(false);
    }
}
