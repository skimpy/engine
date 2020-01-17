<?php namespace spec\Skimpy\File;

use DateTime;
use PhpSpec\ObjectBehavior;
use Skimpy\File\FrontMatter;
use Symfony\Component\Finder\SplFileInfo;
use Skimpy\File\InvalidContentFileLocation;
use Doctrine\Common\Collections\ArrayCollection;

class ContentFileSpec extends ObjectBehavior
{
    function let(SplFileInfo $file, FrontMatter $frontMatter)
    {
        $content = '';
        $this->beConstructedWith($file, $content, $frontMatter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Skimpy\File\ContentFile');
    }

    function it_requires_a_file_object(SplFileInfo $file)
    {
        $this->getFile()->shouldReturn($file);
    }

    function it_requires_a_content_string()
    {
        $this->getContent()->shouldReturn('');
    }

    function it_requires_a_front_matter_object(FrontMatter $frontMatter)
    {
        $this->getFrontMatter()->shouldReturn($frontMatter);
    }

    function it_uses_the_frontmatter_title_value_as_title_when_present(FrontMatter $frontMatter, SplFileInfo $file)
    {
        $frontMatter->has('title')->willReturn(true);
        $frontMatter->getTitle()->willReturn('Some Custom Title');
        $this->getTitle()->shouldReturn('Some Custom Title');
    }

    function it_uses_the_slug_in_title_format_when_no_title_specified(FrontMatter $frontMatter, SplFileInfo $file)
    {
        $frontMatter->has('title')->willReturn(false);

        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('the-file-name');

        $file->getRealPath()->willReturn('/path/to/content/the-file-name.md');
        $this->getTitle()->shouldReturn('The File Name');
    }

    function it_uses_the_filemtime_as_date_if_front_matter_has_no_date_key(SplFileInfo $file, FrontMatter $frontMatter)
    {
        $frontMatter->has('date')->willReturn(false);
        $time = strtotime('December 1st 2015');
        $file->getMTime()->willReturn($time);
        $this->getDate()->format('Y-m-d')->shouldReturn('2015-12-01');
    }

    function it_uses_the_front_matter_datetime_object_if_date_is_quoted_in_front_matter(SplFileInfo $file, FrontMatter $frontMatter)
    {
        $frontMatter->has('date')->willReturn(true);
        $frontMatter->getDate()->willReturn(new Datetime('2014-01-01'));
        $this->getDate()->format('Y-m-d')->shouldReturn('2014-01-01');
    }

    function it_converts_front_matter_date_from_unix_timestamp_to_datetime_if_date_is_not_quoted_in_front_matter(SplFileInfo $file, FrontMatter $frontMatter)
    {
        $frontMatter->has('date')->willReturn(true);
        $frontMatter->getDate()->willReturn(new \DateTime('2020-01-12'));
        $this->getDate()->format('Y-m-d')->shouldReturn('2020-01-12');
    }

    function it_uses_the_front_matter_description_as_the_seo_meta_description_if_it_exists(FrontMatter $frontMatter)
    {
        $frontMatter->has('description')->willReturn(true);
        $frontMatter->getDescription()->willReturn('SEO Meta Description here');
        $this->getDescription()->shouldReturn('SEO Meta Description here');

        $frontMatter->getDescription()->willReturn(null);
        $this->getDescription()->shouldReturn(null);
    }

    function it_uses_front_matter_excerpt_if_it_exists(FrontMatter $frontMatter)
    {
        $frontMatter->has('excerpt')->willReturn(true);
        $frontMatter->getExcerpt()->willReturn('The excerpt');
        $this->getExcerpt()->shouldReturn('The excerpt');

        $frontMatter->getExcerpt()->willReturn(null);
        $this->getExcerpt()->shouldReturn(null);
    }

    function it_uses_front_matter_seo_title_if_it_exists(FrontMatter $frontMatter)
    {
        $frontMatter->getSeoTitle()->willReturn('Some SEO friendly title');
        $this->getSeoTitle()->shouldReturn('Some SEO friendly title');

        $frontMatter->getSeoTitle()->willReturn(null);
        $this->getSeoTitle()->shouldReturn(null);
    }

    function it_uses_the_front_matter_terms_as_terms(FrontMatter $frontMatter)
    {
        $c = new ArrayCollection;
        $frontMatter->getTerms()->willReturn($c);
        $this->getTerms()->shouldReturn($c);
    }

    function it_uses_the_front_matter_taxonomies_as_taxonomies(FrontMatter $frontMatter)
    {
        $c = new ArrayCollection;
        $frontMatter->getTaxonomies()->willReturn($c);
        $this->getTaxonomies()->shouldReturn($c);
    }

    function it_uses_the_front_matter_metadata_as_metadata(FrontMatter $frontMatter)
    {
        $frontMatter->getMetadata()->willReturn([]);
        $this->getMetadata()->shouldReturn([]);
    }

    function it_uses_the_filepath_to_determine_the_content_uri(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('person');
        $file->getRealPath()->willReturn('/path/to/content/our-team/person.md');
        $this->getUri()->shouldReturn('our-team/person');
    }

    function it_does_not_include_the_filename_in_the_uri_if_is_index_file(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('index');
        $file->getRealPath()->willReturn('/path/to/content/blog/index.md');
        $this->getUri()->shouldReturn('blog');

        $file->getRealPath()->willReturn('/path/to/content/foo/blog/index.md');
        $this->getUri()->shouldReturn('foo/blog');
    }

    function it_uses_the_filename_in_the_uri_if_file_is_named_index_and_is_located_at_top_level(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('index');
        $file->getRealPath()->willReturn('/path/to/content/index.md');
        $this->getUri()->shouldReturn('index');
    }

    function it_throws_an_exception_if_the_file_is_not_an_ancestor_if_a_directory_title_content(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('foo');
        $file->getRealPath()->willReturn('/path/to/foo.md');
        $this->shouldThrow(InvalidContentFileLocation::class)->duringGetType();
    }

    function it_uses_the_parent_dir_as_the_content_type(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('index');
        $file->getRealPath()->willReturn('/path/to/content/page/index.md');
        $this->getType()->shouldReturn('page');
    }

    function it_uses_the_default_type_of_entry_when_content_file_is_top_level(SplFileInfo $file)
    {
        $file->getExtension()->willReturn('md');
        $file->getBasename('.md')->willReturn('index');
        $file->getRealPath()->willReturn('/path/to/content/index.md');
        $this->getType()->shouldReturn('entry');
    }

    function it_uses_the_front_matter_template_as_template(FrontMatter $frontMatter, SplFileInfo $file)
    {
        $frontMatter->getTemplate()->willReturn('foo');
        $this->getTemplate()->shouldReturn('foo');

        $frontMatter->getTemplate()->willReturn(null);
        $this->getTemplate()->shouldReturn(null);
    }
}
