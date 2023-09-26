<?php namespace spec\Skimpy\Http\Middleware;

use SplFileInfo;
use Prophecy\Argument;
use PhpSpec\ObjectBehavior;
use Skimpy\Database\Populator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;

class ContentCacheHandlerSpec extends ObjectBehavior
{
    function let(Populator $populator, Filesystem $filesystem)
    {
        $buildIndicator = new SplFileInfo(__DIR__.'/.seeded');

        $this->beConstructedWith($populator, $filesystem, $buildIndicator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\Http\Middleware\ContentCacheHandler');
    }

    function it_always_rebuilds_if_auto_rebuild_config_is_true(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        Filesystem $filesystem
    ) {
        $container->get('skimpy.auto_rebuild')->willReturn(true);

        $this->expectRebuild($populator, $filesystem);

        $this->handleRequest($request, $container);
    }

    function it_builds_the_db_if_there_is_no_db_regardless_of_auto_rebuild(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        Filesystem $filesystem
    ) {
        $this->beConstructedWith($populator, $filesystem, new SplFileInfo('non-existent-file'));

        $container->get('skimpy.auto_rebuild')->willReturn(false);

        $populator->populate()->shouldBeCalled();

        $this->handleRequest($request, $container);
    }

    function it_rebuilds_the_db_if_request_contains_valid_build_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        Filesystem $filesystem,
        ParameterBag $query
    ) {
        $container->get('skimpy.auto_rebuild')->willReturn(false);

        $request->query = $query;
        $query->has('rebuild')->willReturn(true);

        $buildKey = '0000';
        $container->get('skimpy.build_key')->willReturn($buildKey);
        $query->get('rebuild')->willReturn($buildKey);

        $this->expectRebuild($populator, $filesystem);

        $this->handleRequest($request, $container);
    }

    function it_does_not_rebuild_if_request_has_no_rebuild_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        ParameterBag $query
    ) {
        $container->get('skimpy.auto_rebuild')->willReturn(false);

        $request->query = $query;
        $query->has('rebuild')->willReturn(false);

        $populator->populate()->shouldNotBeCalled();

        $this->handleRequest($request, $container);
    }

    function it_does_not_rebuild_if_app_container_has_no_build_key(
        Request $request,
        ContainerInterface $container,
        Populator $populator,
        ParameterBag $query
    ) {
        $container->get('skimpy.auto_rebuild')->willReturn(false);

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
        ParameterBag $query
    ) {
        $container->get('skimpy.auto_rebuild')->willReturn(false);

        $request->query = $query;
        $query->has('rebuild')->willReturn(true);

        $container->has('skimpy.build_key')->willReturn(true);
        $container->get('skimpy.build_key')->willReturn('foo');
        $query->get('rebuild')->willReturn('bar');

        $populator->populate()->shouldNotBeCalled();

        $this->handleRequest($request, $container);
    }

    protected function expectRebuild($populator, $filesystem)
    {
        $populator->populate()->shouldBeCalled();

        $buildIndicatorPath = new SplFileInfo(__DIR__ . '/.seeded');

        $filesystem->remove($buildIndicatorPath)->shouldBeCalled();
        $filesystem->dumpFile($buildIndicatorPath, Argument::type('string'))->shouldBeCalled();
    }
}
