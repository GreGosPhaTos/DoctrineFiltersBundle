<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Annotation;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
final class RecordState
{
    /**
     * @var string
     */
    public $stateFieldName;

    /**
     * @var string
     */
    public $activeValue;

    /**
     * @var string
     */
    public $pendingValue;

    /**
     * @var string
     */
    public $deleteValue;
}
