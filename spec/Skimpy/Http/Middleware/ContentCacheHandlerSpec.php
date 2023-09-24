<?php namespace spec\Skimpy\Http\Middleware;

use SplFileInfo;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Skimpy\Database\Populator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use PhpSpec\Exception\Example\SkippingException;
use Symfony\Component\HttpFoundation\ParameterBag;

class ContentCacheHandlerSpec extends ObjectBehavior
{
    function let(Populator $populator, Filesystem $filesystem)
    {
        $buildIndicator = new SplFileInfo('');

        $this->beConstructedWith($populator, $filesystem, $buildIndicator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\Http\Middleware\ContentCacheHandler');
    }

    function it_always_rebuild_in_dev_env(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        Filesystem $filesystem
    ) {

        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(true);

        $this->expectRebuild($populator, $filesystem, $buildIndicator);

        $this->handleRequest($request, $container);
    }

    function it_rebuilds_if_no_build_indicator_is_found(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        Filesystem $filesystem
    ) {
        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(false);
        $buildIndicator->isFile()->willReturn(false);

        $this->expectRebuild($populator, $filesystem, $buildIndicator);

        $this->handleRequest($request, $container);
    }

    function it_rebuilds_the_db_if_request_contains_valid_build_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        Filesystem $filesystem,
        ParameterBag $query
    ) {
        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(false);
        $buildIndicator->isFile()->willReturn(true);

        $request->query = $query;
        $query->has('rebuild')->willReturn(true);

        $buildKey = '0000';
        $container->get('skimpy.build_key')->willReturn($buildKey);
        $query->get('rebuild')->willReturn($buildKey);

        $this->expectRebuild($populator, $filesystem, $buildIndicator);

        $this->handleRequest($request, $container);
    }

    function it_does_not_rebuild_if_request_has_no_rebuild_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        ParameterBag $query
    ) {
        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(false);
        $buildIndicator->isFile()->willReturn(true);

        $request->query = $query;
        $query->has('rebuild')->willReturn(false);

        $populator->populate()->shouldNotBeCalled();
        $this->handleRequest($request, $container);
    }

    function it_does_not_rebuild_if_app_container_has_no_build_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        ParameterBag $query
    ) {
        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(false);
        $buildIndicator->isFile()->willReturn(true);

        $request->query = $query;
        $query->has('rebuild')->willReturn(true);

        $container->get('skimpy.build_key')->willReturn(null);

        $populator->populate()->shouldNotBeCalled();
        $this->handleRequest($request, $container);
    }

    function it_does_not_rebuild_if_request_build_key_does_not_match_app_build_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        SplFileInfo $buildIndicator,
        ParameterBag $query
    ) {
        throw new SkippingException('Update unable to mock SplFileInfo');

        $container->get('skimpy.auto_rebuild')->willReturn(false);
        $buildIndicator->isFile()->willReturn(true);

        $request->query = $query;
        $query->has('rebuild')->willReturn(true);

        $container->has('skimpy.build_key')->willReturn(true);
        $container->get('skimpy.build_key')->willReturn('foo');
        $query->get('rebuild')->willReturn('bar');

        $populator->populate()->shouldNotBeCalled();
        $this->handleRequest($request, $container);
    }

    protected function expectRebuild($populator, $filesystem, $buildIndicator)
    {
        $populator->populate()->shouldBeCalled();

        $path = 'path/to/.seeded';
        $buildIndicator->getPathname()->willReturn($path);

        $filesystem->remove($path)->shouldBeCalled();
        $filesystem->dumpFile($path, Argument::type('string'))->shouldBeCalled();
    }
}
