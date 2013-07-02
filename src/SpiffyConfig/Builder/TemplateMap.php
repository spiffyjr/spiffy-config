<?php

namespace SpiffyConfig\Builder;

use SpiffyConfig\Resolver;

class TemplateMap implements BuilderInterface
{
    /**
     * {@inheritDoc}
     */
    public function build(Resolver\ResultInterface $result)
    {
        $config = array();

        foreach ($result as $file) {
            $basename = $file->getBasename('.' . $file->getExtension());
            $fullname = sprintf('%s/%s', $file->getRelativePath(), $basename);

            $config['view_manager']['template_map'][$fullname] = $file->getRealPath();
        }

        return $config;
    }
}
