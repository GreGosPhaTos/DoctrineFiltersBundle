<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ODM;

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\Common\Annotations\Reader;
use GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\AnnotationReaderAwareInterface;

class Configurator
{
    /**
     * @var DocumentManager
     */
    protected $dm;

    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(DocumentManager $dm, Reader $reader)
    {
        $this->dm = $dm;
        $this->reader = $reader;
    }

    public function onKernelRequest()
    {
        $filter = $this->dm->getFilterCollection()->enable('state_filter');
        $this->setReaderToFilter($filter, $this->reader);
    }

    public function setReaderToFilter(AnnotationReaderAwareInterface $filter, Reader $reader)
    {
        $filter->setAnnotationReader($reader);
    }
}
