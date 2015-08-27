<?php

namespace GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ORM;

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
    private $mockEM;

    public function setUp()
    {
        $this->mockReader = $this->getMockBuilder('Doctrine\Common\Annotations\Reader')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockClassMetadata = $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetaData')
            ->disableOriginalConstructor()
            ->getMock();

        $this->mockEM = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testAddFilterConstraintEmptyReader()
    {
        $recordStateFilter = new RecordStateFilter($this->mockEM, 'table');
        $this->assertEmpty($recordStateFilter->addFilterConstraint($this->mockClassMetadata, 'table'));
    }

    public function testAddFilterConstraintEmptyRecordState()
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
        $recordStateFilter = new RecordStateFilter($this->mockEM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEmpty($recordStateFilter->addFilterConstraint($this->mockClassMetadata, 'table'));
    }

    /**
     * @dataProvider recordStateProvider
     */
    public function testAddFilterConstraintInvalidRecordState(RecordState $recordState)
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
        $recordStateFilter = new RecordStateFilter($this->mockEM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEmpty($recordStateFilter->addFilterConstraint($this->mockClassMetadata, 'table'));
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

    public function testAddFilterConstraint()
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
        $recordStateFilter = new RecordStateFilter($this->mockEM);
        $recordStateFilter->setAnnotationReader($this->mockReader);
        $this->assertEquals($recordStateFilter->addFilterConstraint($this->mockClassMetadata, 'table'), sprintf('%s.%s = "%s"', 'table', $recordState->stateFieldName, $recordState->activeValue));
    }
}
