<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ODM;

use Doctrine\ODM\MongoDB\Mapping\ClassMetaData;
use Doctrine\ODM\MongoDB\Query\Filter\BsonFilter;
use Doctrine\Common\Annotations\Reader;
use GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\AnnotationReaderAwareInterface;

class RecordStateFilter extends BsonFilter implements AnnotationReaderAwareInterface
{
    /**
     * @var Reader
     */
    protected $reader;

    public function addFilterCriteria(ClassMetadata $targetDocument)
    {
        if (empty($this->reader)) {
            return '';
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        $recordState = $this->reader->getClassAnnotation(
            $targetDocument->getReflectionClass(),
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

        return array($stateFieldName => $activeValue);
    }

    public function setAnnotationReader(Reader $reader)
    {
        $this->reader = $reader;
    }
}
