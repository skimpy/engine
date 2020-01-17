<?php namespace spec\Skimpy\Http\Renderer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Skimpy\Contracts\Entity;

class JsonRendererSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\Http\Renderer\JsonRenderer');
    }

    function it_should_throw_not_supported_exception(Entity $entity, Request $request)
    {
        $this->shouldThrow(new \RuntimeException('JSON responses not yet supported'))
            ->duringRender($entity, $request);
    }

    function it_should_accept_application_json()
    {
        $this->getMimeTypes()->shouldReturn(['application/json']);
    }
}
