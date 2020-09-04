<?php

declare(strict_types=1);

namespace Bluetel\EzEntrypointComponentBundle\Component;

use Twig\Environment;
use EzSystems\EzPlatformAdminUi\Component\Renderable;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;

/**
 * Pulls in all assets (CSS + JS) for a given entrypoint
 */
class EntrypointComponent implements Renderable
{
    /** @var Environment */
    protected $twig;

    /** @var string */
    protected $entrypointName;

    /** @var string */
    protected $entryName;

    /** @var EntrypointLookupCollectionInterface */
    protected $entrypointLookupCollection;

    /**
     * @param Environment $twig
     * @param ContainerInterface $container
     * @param string $entryName
     * @param string $entrypointName
     */
    public function __construct(
        Environment $twig,
        EntrypointLookupCollectionInterface $entrypointLookupCollection,
        string $entryName,
        string $entrypointName = '_default'
    ) {
        $this->twig = $twig;
        $this->entrypointLookupCollection = $entrypointLookupCollection;
        $this->entryName = $entryName;
        $this->entrypointName = $entrypointName;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function render(array $parameters = []): string
    {
        $entrypointLookup = $this->entrypointLookupCollection->getEntrypointLookup($this->entrypointName);
        return $this->twig->render('@EzEntrypointComponent/admin-ui/entrypoint-assets.html.twig', [
            'scripts' => $entrypointLookup->getJavaScriptFiles($this->entryName),
            'stylesheets' => $entrypointLookup->getCssFiles($this->entryName)
        ] + $parameters);
    }
}
