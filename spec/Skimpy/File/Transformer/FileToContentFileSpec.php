<?php namespace spec\Skimpy\File\Transformer;

use Prophecy\Argument;
use Skimpy\CMS\Taxonomy;
use Skimpy\CMS\Term;
use Skimpy\CMS\ContentItem;
use Skimpy\File\ContentFile;
use Skimpy\File\Transformer\ArrayToFrontMatter;
use spec\Skimpy\ObjectBehavior;
use Skimpy\Symfony\FinderFactory;
use Symfony\Component\Finder\SplFileInfo;
use Doctrine\Common\Collections\ArrayCollection;
use Skimpy\File\FrontMatter;
use Michelf\Markdown;

class FileToContentFileSpec extends ObjectBehavior
{
    function let(
        ArrayToFrontMatter $arrayToFrontMatter
    ) {
        $this->beConstructedWith($arrayToFrontMatter, null, null);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\Transformer\FileToContentFile');
    }

    function it_transforms_an_splfileinfo_object_into_a_content_file(
        ArrayToFrontMatter $arrayToFrontMatter,
        FrontMatter $frontMatter,
        SplFileInfo $file
    ) {
        $file->getContents()->willReturn($this->getContentFile()->getContents());
        $file->getFilename()->willReturn($this->getContentFile()->getFilename());

        $data = [
            "title" => "Foo",
            "date" => '2015-05-18',
            "seotitle" => "foo",
            "categories" => ["Web Development"],
            "tags" => ["Tag 1", "Tag 2"]
        ];

        $arrayToFrontMatter->transform($data)->willReturn($frontMatter);

        $this->transform($file)
            ->shouldReturnAnInstanceOf(ContentFile::class)
        ;
    }

    function it_does_not_transform_front_matter_if_no_front_matter_separator_exists(
        ArrayToFrontMatter $arrayToFrontMatter,
        SplFileInfo $file,
        Markdown $markdown
    ) {
        $this->beConstructedWith($arrayToFrontMatter, $markdown, null);

        $file->getContents()->willReturn('# Foo');

        $markdown->transform('# Foo')->shouldBeCalled();

        $arrayToFrontMatter->transform(Argument::any())->shouldNotBeCalled();

        $this->transform($file)->shouldReturnAnInstanceOf(ContentFile::class);
    }

    function it_gets_content_of_the_file_as_html()
    {
        $examplePost = $this->getContentFile()->getContents();
        $this->getHtml($examplePost)->shouldStartWith('<h2>We happy?</h2>');
    }

    protected function getContentFile($name = 'example.md')
    {
        $contentFile = null;
        $factory = new FinderFactory;
        $finder = $factory->createFinder();

        $dataDir = $this->getContentDir();
        $files = $finder->name($name)->in($dataDir);

        foreach ($files as $file) {
            $contentFile = $file;
        }

        return $contentFile;
    }
}