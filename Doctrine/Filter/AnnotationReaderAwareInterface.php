<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter;

use Doctrine\Common\Annotations\Reader;

interface AnnotationReaderAwareInterface
{
    /**
     * @param Reader $reader
     *
     * @return mixed
     */
    public function setAnnotationReader(Reader $reader);
}
