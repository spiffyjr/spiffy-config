<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver;
use Symfony\Component\Finder\SplFileInfo;

class TemplateMapBuilder implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(Resolver\ResultInterface $result)
    {
        $config = array();

        foreach ($result as $file) {
            if (!$file instanceof SplFileInfo) {
                throw new \RuntimeException(
                    'Builder only operates on Resolver\File results'
                );
            }

            $basename = $file->getBasename('.' . $file->getExtension());
            $fullname = sprintf('%s/%s', $file->getRelativePath(), $basename);

            $config['view_manager']['template_map'][$fullname] = $file->getRealPath();
        }

        return $config;
    }
}
