<?php namespace spec\Skimpy\Http\Renderer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\Contracts\Entity;
use Skimpy\Http\Renderer\TwigRenderer;
use Symfony\Component\HttpFoundation\Request;
use Twig_Environment;

class TwigRendererSpec extends ObjectBehavior
{
    function let(Twig_Environment $twig)
    {
        $this->beConstructedWith($twig);
    }

    function xit_is_initializable()
    {
        $this->shouldHaveType(TwigRenderer::class);
    }

    function xit_renders_an_entity_to_an_html_response(
        Entity $entity,
        Request $request
    ) {
        $this->render($entity, $request);
    }
}
