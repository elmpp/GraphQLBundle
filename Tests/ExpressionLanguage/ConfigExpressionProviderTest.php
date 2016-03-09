<?php

/*
 * This file is part of the OverblogGraphQLBundle package.
 *
 * (c) Overblog <http://github.com/overblog/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Overblog\GraphQLBundle\Tests\Error;

use Overblog\GraphQLBundle\ExpressionLanguage\ExpressionLanguage;
use Overblog\GraphQLBundle\Tests\DIContainerMockTrait;

class ConfigExpressionProviderTest extends \PHPUnit_Framework_TestCase
{
    use DIContainerMockTrait;

    /**
     * @var ExpressionLanguage
     */
    private $expressionLanguage;

    public function setUp()
    {
        $this->expressionLanguage = new ExpressionLanguage();
        $container = $this->getDIContainerMock();
        $this->expressionLanguage->setContainer($container);
    }

    public function testService()
    {
        $object = new \stdClass();
        $container = $this->getDIContainerMock(['toto' => $object]);
        $this->expressionLanguage->setContainer($container);
        $this->assertEquals($object, $this->expressionLanguage->evaluate('service("toto")'));
    }

    public function testParameter()
    {
        $container = $this->getDIContainerMock([], ['test' => 5]);
        $this->expressionLanguage->setContainer($container);
        $this->assertEquals(5, $this->expressionLanguage->evaluate('parameter("test")'));
    }

    public function testIsTypeOf()
    {
        $this->assertTrue($this->expressionLanguage->evaluate(sprintf('isTypeOf("%s")', 'stdClass'), ['value' => new \stdClass()]));
    }

    public function testNewObject()
    {
        $this->assertInstanceOf('stdClass', $this->expressionLanguage->evaluate(sprintf('newObject("%s")', 'stdClass')));
    }

    public function testFromGlobalId()
    {
        $this->assertEquals(['type' => 'User', 'id' => 15], $this->expressionLanguage->evaluate('fromGlobalId("VXNlcjoxNQ==")'));
    }

    public function testGlobalId()
    {
        $this->assertEquals('VXNlcjoxNQ==', $this->expressionLanguage->evaluate('globalId(15, "User")'));
    }
}
