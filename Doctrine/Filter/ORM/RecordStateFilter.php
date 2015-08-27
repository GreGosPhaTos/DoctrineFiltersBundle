<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ORM;

use Doctrine\ORM\Mapping\ClassMetaData;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\Common\Annotations\Reader;
use GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\AnnotationReaderAwareInterface;

class RecordStateFilter extends SQLFilter implements AnnotationReaderAwareInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (empty($this->reader)) {
            return '';
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "active" (marked with an annotation)
        $recordState = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            'GreGosPhaTos\\DoctrineFiltersBundle\\Doctrine\\Annotation\\RecordState'
        );

        if (!$recordState) {
            return '';
        }

        $stateFieldName = $recordState->stateFieldName;
        $activeValue = $recordState->activeValue;

        if (empty($stateFieldName) || empty($activeValue)) {
            return '';
        }

        $query = sprintf('%s.%s = "%s"', $targetTableAlias, $stateFieldName, $activeValue);

        return $query;
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
