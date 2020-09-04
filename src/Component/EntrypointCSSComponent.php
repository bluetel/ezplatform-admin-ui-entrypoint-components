<?php

declare(strict_types=1);

namespace Bluetel\EzEntrypointComponentBundle\Component;

use Twig\Environment;
use EzSystems\EzPlatformAdminUi\Component\Renderable;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollectionInterface;

/**
 * Pulls in all scripts for a given entrypoint
 */
class EntrypointCSSComponent implements Renderable
{
    /** @var Environment */
    protected $twig;

    /** @var string */
    protected $entrypointName;

    /** @var string */
    protected $entryName;

    /** @var EntrypointLookupCollectionInterface */
    protected $entrypointLookupCollection;

    /** @var string|null */
    protected $crossorigin;

    /** @var string|null */
    protected $integrity;

    /**
     * @param Environment $twig
     * @param EntrypointLookupCollectionInterface $entrypointLookupCollection
     * @param string $entryName
     * @param string $entrypointName
     * @param string|null $crossorigin
     * @param string|null $integrity
     */
    public function __construct(
        Environment $twig,
        EntrypointLookupCollectionInterface $entrypointLookupCollection,
        string $entryName,
        string $entrypointName = '_default',
        string $crossorigin = null,
        string $integrity = null
    ) {
        $this->twig = $twig;
        $this->entrypointLookupCollection = $entrypointLookupCollection;
        $this->entryName = $entryName;
        $this->entrypointName = $entrypointName;
        $this->crossorigin = $crossorigin;
        $this->integrity = $integrity;
    }

    /**
     * @param array $parameters
     *
     * @return string
     */
    public function render(array $parameters = []): string
    {
        $entrypointLookup = $this->entrypointLookupCollection->getEntrypointLookup($this->entrypointName);
        return $this->twig->render('@EzEntrypointComponent/admin-ui/components/entrypoint-stylesheet.html.twig', [
            'stylesheets' => $entrypointLookup->getCssFiles($this->entryName),
            'crossorigin' => $this->crossorigin,
            'integrity' => $this->integrity,
        ] + $parameters);
    }
}
