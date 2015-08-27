<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ORM;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Annotations\Reader;
use GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\AnnotationReaderAwareInterface;

class Configurator
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Reader
     */
    protected $reader;

    public function __construct(EntityManager $em, Reader $reader)
    {
        $this->em = $em;
        $this->reader = $reader;
    }

    public function onKernelRequest()
    {
        $filter = $this->em->getFilters()->enable('state_filter');
        $this->setReaderToFilter($filter, $this->reader);
    }

    public function setReaderToFilter(AnnotationReaderAwareInterface $filter, Reader $reader)
    {
        $filter->setAnnotationReader($reader);
    }
}
