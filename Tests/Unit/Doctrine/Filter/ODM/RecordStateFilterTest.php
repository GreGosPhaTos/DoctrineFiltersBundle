<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ODM;

use GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Annotation\RecordState;

class RecordStateFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockClassMetadata;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockReader;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $mockDM;

    public function setUp()
    {
        $this->mockReader = $this->getMockBuilder('Doctrine\Common\Annotations\Reader')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockClassMetadata = $this->getMockBuilder('Doctrine\ODM\MongoDB\Mapping\ClassMetaData')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockDM = $this->getMockBuilder('Doctrine\ODM\MongoDB\DocumentManager')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAddFilterCriteriaEmptyReader()
    {
        $recordStateFilter = new RecordStateFilter($this->mockDM);
        $this->assertEmpty($recordStateFilter->addFilterCriteria($this->mockClassMetadata));
    }

    public function testAddFilterCriteriaEmptyRecordState()
    {
        $reflectionClass = new \ReflectionClass(new \stdClass());
        $this->mockClassMetadata
            ->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue($reflectionClass));
        $this->mockReader
            ->expects($this->once())
            ->method('getClassAnnotation')
            ->with($this->equalTo($reflectionClass), $this->equalTo('GreGosPhaTos\\DoctrineFiltersBundle\\Doctrine\\Annotation\\RecordState'));
        $recordStateFilter = new RecordStateFilter($this->mockDM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEmpty($recordStateFilter->addFilterCriteria($this->mockClassMetadata));
    }

    /**
     * @dataProvider recordStateProvider
     */
    public function testAddFilterCriteriaInvalidRecordState(RecordState $recordState)
    {
        $reflectionClass = new \ReflectionClass(new \stdClass());
        $this->mockClassMetadata
            ->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue($reflectionClass));
        $this->mockReader
            ->expects($this->once())
            ->method('getClassAnnotation')
            ->with($this->equalTo($reflectionClass), $this->equalTo('GreGosPhaTos\\DoctrineFiltersBundle\\Doctrine\\Annotation\\RecordState'))
            ->willReturn($recordState);
        $recordStateFilter = new RecordStateFilter($this->mockDM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEmpty($recordStateFilter->addFilterCriteria($this->mockClassMetadata));
    }

    public function recordStateProvider()
    {
        $recordState1 = new RecordState();
        $recordState2 = new RecordState();
        $recordState3 = new RecordState();
        $recordState2->activeValue = 'foo';
        $recordState3->stateFieldName = 'foo';

        return array(
            array($recordState1),
            array($recordState2),
            array($recordState3),
        );
    }

    public function testAddFilterCriteria()
    {
        $reflectionClass = new \ReflectionClass(new \stdClass());
        $recordState = new RecordState();
        $recordState->stateFieldName = 'foo';
        $recordState->activeValue = 'bar';

        $this->mockClassMetadata
            ->expects($this->once())
            ->method('getReflectionClass')
            ->will($this->returnValue($reflectionClass));
        $this->mockReader
            ->expects($this->once())
            ->method('getClassAnnotation')
            ->with($this->equalTo($reflectionClass), $this->equalTo('GreGosPhaTos\\DoctrineFiltersBundle\\Doctrine\\Annotation\\RecordState'))
            ->willReturn($recordState);
        $recordStateFilter = new RecordStateFilter($this->mockDM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEquals(
            $recordStateFilter->addFilterCriteria($this->mockClassMetadata),
            array(
                $recordState->stateFieldName => $recordState->activeValue,
            )
        );
    }
}
