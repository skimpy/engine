<?php namespace spec\Skimpy\Http\Renderer;

use Negotiation\Negotiator;
use PhpSpec\ObjectBehavior;
use Skimpy\Contracts\Entity;
use Skimpy\Http\Renderer\JsonRenderer;
use Skimpy\Http\Renderer\TwigRenderer;
use Skimpy\Http\Renderer\NegotiatingRenderer;
use Symfony\Component\HttpFoundation\Request;

class NegotiatingRendererSpec extends ObjectBehavior
{
    function let(
        JsonRenderer $jsonRenderer,
        TwigRenderer $twigRenderer
    ) {
        $renderers = [$jsonRenderer, $twigRenderer];
        $defaultMimeType = 'text/html';

        $this->beConstructedWith($renderers, new Negotiator, $defaultMimeType);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NegotiatingRenderer::class);
    }

    function it_uses_the_twig_renderer_when_accept_type_is_text_html(
        Entity $entity,
        TwigRenderer $twigRenderer,
        JsonRenderer $jsonRenderer
    ) {
        $server = [
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        ];

        $request = Request::create('/foo', 'GET', [], [], [], $server);

        $jsonRenderer->getMimeTypes()->willReturn(['application/json']);
        $twigRenderer->getMimeTypes()->willReturn(['text/html']);

        $twigRenderer->render($entity, $request, [])->shouldBeCalled();

        $this->render($entity, $request, []);
    }

    function xit_uses_the_json_renderer_when_accept_type_is_application_json(
        Entity $entity,
        TwigRenderer $twigRenderer,
        JsonRenderer $jsonRenderer
    ) {
        $server = [
            'HTTP_ACCEPT' => 'application/json',
        ];

        $request = Request::create('/foo', 'GET', [], [], [], $server);

        $jsonRenderer->getMimeTypes()->willReturn(['application/json']);
        $twigRenderer->getMimeTypes()->willReturn(['text/html']);

        $jsonRenderer->render($entity, $request, [])->shouldBeCalled();

        $this->render($entity, $request, []);
    }

    function it_defaults_to_twig_renderer_when_no_valid_accept_type_is_found(
        Entity $entity,
        TwigRenderer $twigRenderer,
        JsonRenderer $jsonRenderer
    ) {
        $server = [
            'HTTP_ACCEPT' => 'invalid/type',
        ];

        $request = Request::create('/foo', 'GET', [], [], [], $server);

        $jsonRenderer->getMimeTypes()->willReturn(['application/json']);
        $twigRenderer->getMimeTypes()->willReturn(['text/html']);

        $twigRenderer->render($entity, $request, [])->shouldBeCalled();

        $this->render($entity, $request, []);
    }
}
