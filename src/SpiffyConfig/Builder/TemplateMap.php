<?php

namespace SpiffyConfig\Builder;

class TemplateMap extends AbstractBuilder
{
    public function build()
    {
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        $finder = $this->resolver->resolve();
        $config = array();

        foreach ($finder as $file) {
            $basename = $file->getBasename('.' . $file->getExtension());
            $fullname = sprintf('%s/%s', $file->getRelativePath(), $basename);

            $config['view_manager']['template_map'][$fullname] = $file->getRealPath();
        }

        return $config;
    }
}
