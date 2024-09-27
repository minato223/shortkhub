<?php

namespace App\AppGenerator\UI\Service;

use App\AppGenerator\Domain\Entity\Project;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\RequestStack;

class AppGeneratorService
{
    public function __construct(
        private readonly UploaderHelper $uploaderHelper,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly RequestStack $requestStack
    ) {}

    public function updateIconPath(Project $project): Project
    {

        $project->iconPath = sprintf('%s/%s', $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(), $this->uploaderHelper->asset($project->getIcon()));
        return $project;
    }

    public function toArray(Project $project): array
    {
        return [
            'name' => $project->getName(),
            'description' => $project->getDescription(),
            'url' => $project->getUrl(),
            'icon' => $this->updateIconPath($project)->iconPath,
            'uuid' => $project->getUuid(),
            'redirect' => sprintf('%s%s', $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost(), $this->urlGenerator->generate('app_generator_detail', ['uuid' => $project->getUuid()]))
        ];
    }

    public static function toSnakeCase($input)
    {
        $inputString = str_replace(' ', '_', $input);

        // Ajouter des underscores avant chaque majuscule qui ne se trouve pas en début de chaîne
        $inputString = preg_replace('/(?<!^)(?=[A-Z])/', '_', $inputString);

        // Convertir en minuscules
        return strtolower($inputString);
    }
}
