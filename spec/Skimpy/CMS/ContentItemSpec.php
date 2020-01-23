<?php namespace spec\Skimpy\CMS;

use DateTime;
use Skimpy\CMS\Term;
use Skimpy\CMS\Taxonomy;
use PhpSpec\ObjectBehavior;
use Skimpy\CMS\ContentItem;
use Doctrine\Common\Collections\ArrayCollection;

class ContentItemSpec extends ObjectBehavior
{
    function let()
    {
        $fields = $this->getData();

        $this->beConstructedWith(
            $fields['uri'],
            $fields['title'],
            $fields['date'],
            $fields['type'],
            $fields['content'],
            $fields['template'],
            $fields['description'],
            $fields['seoTitle'],
            $fields['excerpt']
        );
    }

    protected function getData()
    {
         return [
            'uri'         => 'foo/hello-world',
            'title'       => 'Hello World',
            'date'        => new DateTime('2015-01-01'),
            'type'        => 'post',
            'content'     => 'the content here',
            'template'    => 'post',
            'description' => 'SEO meta description',
            'seoTitle'    => 'The SEO Title',
            'excerpt'     => 'The excerpt',
        ];
    }

    protected function constructWithoutOptional(array $data = [])
    {
        $optional = [
            'description' => null,
            'seoTitle'    => null,
            'excerpt'     => null
        ];

        $fields = array_values(array_merge($this->getData(), $optional, $data));

        $this->beConstructedWith(...$fields);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContentItem::class);
    }

    function it_can_be_constructed_from_an_array()
    {
        $data = $this->getData();

        $content = $this::fromArray($data);
        $content->shouldHaveType(ContentItem::class);
        $content->getUri()->shouldReturn($data['uri']);
        $content->getSlug()->shouldReturn('hello-world');

        $content->getDate()->format('Y-m-d')->shouldReturn($data['date']->format('Y-m-d'));

        $content->getTitle()->shouldReturn($data['title']);
        $content->getType()->shouldReturn($data['type']);
        $content->getContent()->shouldReturn($data['content']);
        $content->getTemplate()->shouldReturn($data['template']);
        $content->getDescription()->shouldReturn($data['description']);
        $content->getSeoTitle()->shouldReturn($data['seoTitle']);
        $content->getExcerpt()->shouldReturn($data['excerpt']);

        $content->getTerms()->shouldHaveType(ArrayCollection::class);
        $content->getMetadata()->shouldReturn([]);
    }

    function it_can_store_a_unique_integer_id()
    {
    	$this->getId()->shouldReturn(null);
    }

    function it_has_a_unique_string_key()
    {
    	$this->getKey()->shouldReturn($this->getUri());
    }

    function it_requires_a_uri()
    {
    	$this->getUri()->shouldReturn($this->getData()['uri']);
    }

    function it_requires_a_title()
    {
        $this->getTitle()->shouldReturn('Hello World');
    }

    function it_requires_a_date()
    {
        $this->getDate()->format('Y-m-d')->shouldReturn('2015-01-01');
    }

    function it_requires_a_type()
    {
        $this->getType()->shouldReturn('post');
    }

    function it_determines_the_slug_using_the_uri()
    {
        $this->getSlug()->shouldReturn('hello-world');
    }

    /**
     * Calling contentItem->taxonomyKey() (post->categories())
     * should return an array collection of terms in the "categories"
     * taxonomy that are assigned to this post.
     */
    function it_returns_a_taxonomy_if_you_access_a_property_matching_the_taxonomy_key()
    {
        $term = $this->getTestTerm();

        $this->addTerm($term);

        $this->categories()->shouldHaveType(ArrayCollection::class);
    }

    function it_throws_an_exception_when_you_try_and_access_a_property_that_does_not_exist_and_is_not_a_taxonomy_key()
    {
        $this->shouldThrow('\RuntimeException')->during('__get', ['genres']);
    }

    function it_falls_back_on_title_if_seotitle_is_null()
    {
        $fields = $this->getData();
        $this->constructWithoutOptional();
        $this->getSeoTitle()->shouldReturn($fields['title']);
    }

    function it_can_store_and_retrieve_metadata()
    {
        $this->setMeta('foo', 'bar');
        $this->getMeta('foo')->shouldReturn('bar');
        $this->removeMeta('foo')->shouldReturn('bar');
        $this->getMeta('foo')->shouldReturn(null);
    }

    function it_does_not_allow_reference_modification_of_metadata_when_getting_all_metadata()
    {
        $this->setMeta('foo', 'bar');
        $meta = $this->getMetadata();
        $meta['baz'] = 'qux';

        $this->getMetadata()->shouldNotHaveKey('baz');
    }

    function it_can_store_a_description_for_seo_purposes()
    {
        $this->getDescription()->shouldReturn('SEO meta description');
    }

    function it_will_fallback_on_the_content_when_excerpt_is_null()
    {
        $this->constructWithoutOptional();
        $this->getExcerpt()->shouldEqual('the content here');
    }

    function it_will_respect_the_max_excerpt_length()
    {
        $content = 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.';
        $this->constructWithoutOptional(['content' => $content]);

        $this->getContent()->shouldReturn($content);
        $this->getExcerpt()->shouldHaveLength(ContentItem::EXCERPT_LENGTH);
    }

    function it_does_not_include_html_tags_in_the_excerpt()
    {
        $content = "<p>Lorem ipsum dolor sit amet, <script></script>consectetur adipisicing elit</p>";
        $this->constructWithoutOptional(['content' => $content]);
        $this->getExcerpt()->shouldReturn(strip_tags($content));
    }

    function it_knows_what_terms_it_has(Term $term)
    {
        $this->addTerm($term);
        $this->shouldHaveTerm($term);
    }

    function it_can_remove_terms_from_itself(Term $term)
    {
        $this->addTerm($term);
        $this->removeTerm($term);
        $this->shouldNotHaveTerm($term);
    }

    public function getMatchers(): array
    {
        return [
            'haveLength' => function ($subject, $length) {
                return strlen($subject) === $length;
            }
        ];
    }

    protected function getTestTaxonomy()
    {
        $tax = [
            'name'       => 'Category',
            'pluralName' => 'Categories',
            'uri'        => 'categories'
        ];

        return Taxonomy::fromArray($tax);
    }

    protected function getTestTerm()
    {
        $term = [
            'taxonomy' => $this->getTestTaxonomy(),
            'name'     => 'Web Development',
            'slug'     => 'web-development'
        ];

        return Term::fromArray($term);
    }
}