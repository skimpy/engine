<?php namespace spec\Skimpy\File\Transformer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Skimpy\File\Transformer\FileToContentFile;
use Skimpy\File\ContentFile;
use Skimpy\CMS\Term;
use Skimpy\CMS\ContentItem;
use Symfony\Component\Finder\SplFileInfo;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use Skimpy\File\Transformer\TransformationFailure;

class FileToContentItemSpec extends ObjectBehavior
{
    function let(FileToContentFile $transformer)
    {
        $this->beConstructedWith($transformer);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\Transformer\FileToContentItem');
    }

    function it_transforms_a_content_file_object_into_a_content_item_object(
        FileToContentFile $transformer,
        SplFileInfo $file,
        ContentFile $contentFile,
        Term $term
    ) {
        $transformer->transform($file)->willReturn($contentFile);
        $contentFile->getUri()->willReturn('the-filename');
        $contentFile->getTitle()->willReturn('The Title');
        $contentFile->getDate()->willReturn(new DateTime('2016-01-01'));
        $contentFile->getType()->willReturn('post');
        $contentFile->getContent()->willReturn('<h1>Hello World</h1>');
        $contentFile->getTemplate()->willReturn('post');
        $contentFile->getDescription()->willReturn('SEO meta description');
        $contentFile->getSeoTitle()->willReturn('SEO Title');
        $contentFile->getExcerpt()->willReturn('The excerpt');
        $contentFile->isIndex()->willReturn(false);
        $contentFile->getTerms()->willReturn(new ArrayCollection([$term->getWrappedObject()]));

        $contentFile->getMetadata()->willReturn(['foo' => 'bar']);

        $this->transform($file)->shouldReturnAnInstanceOf(ContentItem::class);
        $this->transform($file)->getTerms()->toArray()->shouldContain($term->getWrappedObject());
        $this->transform($file)->getMetadata()->shouldReturn(['foo' => 'bar']);
    }

    function it_throws_a_transformation_failure_if_transform_fails(
        FileToContentFile $transformer,
        SplFileInfo $file
    ) {
        $transformer->transform($file)->willThrow(new \Exception('Some message'));
        $this->shouldThrow(new TransformationFailure('Some message'))->duringTransform($file);
    }
}
