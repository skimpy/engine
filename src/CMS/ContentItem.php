<?php

declare(strict_types=1);

namespace Skimpy\CMS;

use DateTime;
use RuntimeException;
use Skimpy\Contracts\Entity;
use Doctrine\ORM\Mapping as ORM;
use Skimpy\Contracts\Classifiable;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Skimpy\Repo\Entries")
 * @ORM\Table(name="content_item")
 */
class ContentItem implements Classifiable, Entity
{
    /*
     * Metadata is any YAML front matter key that is not a property
     * of this class and is not a registered taxonomy key.
     */
    use Metadata;

    const EXCERPT_LENGTH = 255;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Web URI of the ContentItem
     *
     * The URI is determined by the path of the
     * the to the content file and the content file name.
     *
     * @ORM\Column(type="string")
     */
    protected $uri;

    /**
     * Slug is taken from the name of the file
     *
     * @ORM\Column(type="string")
     *
     * @var string
     */
    protected $slug;

    /**
     * Title is specified in YAML front matter
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * Date is specified in YAML front matter
     *
     * @ORM\Column(type="datetime")
     */
    protected $date;

    /**
     * The content type (posts, pages, products, blog, news)
     *
     * Type is determined by the name of the upper most parent folder
     *
     * @ORM\Column(type="string", length=40)
     */
    protected $type;

    /**
     * @ORM\Column(type="text")
     */
    protected $content = '';

    /**
     * The description to be placed inside an HTML meta tag
     * for SEO purposes.
     *
     * Description is specified in the YAML front matter
     *
     * Example usage:
     * <meta name="Description" content="<?php echo $description ?>">
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * Excerpt is specified in YAML front matter
     *
     * It defaults to a substring of the content
     *
     * @ORM\Column(type="text")
     */
    protected $excerpt;

    /**
     * seoTitle is used in the html title tag
     *
     * It is specified in YAML front matter and
     * defaults to the title
     *
     * @ORM\Column(type="string")
     */
    protected $seoTitle;

    /**
     * The html template to use for displaying the content.
     *
     * @ORM\Column(type="string", length=40, nullable=true)
     */
    protected $template;

    /**
     * True if the entry lists other entries of its type.
     *
     * @ORM\Column(type="boolean", name="is_index")
     */
    protected $isIndex = false;

    /**
     * Terms are specified in the YAML front matter
     *
     * If the yaml key matches a taxonomy key, then the key value
     * should be an array of term names.
     *
     * Example YAML: categories: [Web Development, Unix]
     *
     * @ORM\ManyToMany(targetEntity="Skimpy\CMS\Term", inversedBy="contentItems")
     *
     * @var ArrayCollection|Term[]
     */
    protected $terms;

    public function __construct(
        string $uri,
        string $title,
        DateTime $date,
        string $type,
        string $content,
        ?string $template = null,
        ?string $description = null,
        ?string $seoTitle = null,
        ?string $excerpt = null,
        ?bool $isIndex = false
    ) {
        $this->uri = $uri;
        $this->slug = $this->slugFromUri($uri);
        $this->title = $title;
        $this->date = $date;
        $this->type = $type;
        $this->content = $content;
        $this->template = $template;
        $this->setDescription($description);
        $this->seoTitle = $seoTitle ?: $title;
        $this->setExcerpt($excerpt ?: $content);
        $this->isIndex = $isIndex;
        $this->terms = new ArrayCollection;
    }

    public static function fromArray(array $data): self
    {
        return new static(
            $data['uri'],
            $data['title'],
            $data['date'],
            $data['type'],
            $data['content'],
            isset($data['template']) ? $data['template'] : null,
            isset($data['description']) ? $data['description'] : null,
            isset($data['seoTitle']) ? $data['seoTitle'] : null,
            isset($data['excerpt']) ? $data['excerpt'] : null,
            isset($data['isIndex']) ? $data['isIndex'] : false
        );
    }

    /**
     * Get magic method is used to provide dynamic access to taxonomies
     *
     * If this content item doesn't have the taxonomy, then return null
     *
     * Example usage in twig:
     * {% for cat in post.categories %}
     * => Returns an array of terms belonging to the category taxonomy
     *    and to this content item.
     *
     * @param string $method Name of the taxonomy that access is attempted on
     */
    public function __call($method, $args): ?Taxonomy
    {
        if ($this->hasTaxonomy($method)) {
            return $this->getTaxonomy($method);
        }

        throw new RuntimeException(__CLASS__." doesn't contain property or taxonomy: $method");
    }

    public function getKey(): string
    {
        return $this->getUri();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    protected function slugFromUri($uri): string
    {
        return basename($uri);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSeoTitle(): ?string
    {
        return $this->seoTitle;
    }

    public function getDate(): DateTime
    {
        return new class($this->date->format('Y-m-d H:i:s')) extends DateTime
        {
            public function __toString(): string
            {
                return $this->format(config('skimpy.site.date_format'));
            }
        };
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    protected function setExcerpt(string $excerpt): self
    {
        $this->excerpt = substr(strip_tags($excerpt), 0, static::EXCERPT_LENGTH);

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    protected function setDescription(?string $description = null): self
    {
        $this->description = $description ? strip_tags($description) : $description;

        return $this;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    /**
     * Returns the content type (page, post, product, etc)
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return ArrayCollection|Term[]
     */
    public function getTerms(): ArrayCollection
    {
        return $this->terms;
    }

    public function addTerm(Term $term): self
    {
        $term->addContentItem($this);
        $this->terms->add($term);

        return $this;
    }

    public function hasTerm(Term $term): bool
    {
        return $this->terms->contains($term);
    }

    public function removeTerm(Term $term): self
    {
        $term->removeContentItem($this);
        $this->terms->removeElement($term);

        return $this;
    }

    public function getTaxonomy(string $taxKey): Taxonomy
    {
        foreach ($this->getTaxonomies() as $tax) {
            if ($taxKey === $tax->getKey()) {
                return $tax;
            }
        }

        throw new RuntimeException("Content item {$this->slug} has no taxonomy with a key of $taxKey");
    }

    /**
     * @param string $taxKey 'categories', 'tags', etc
     */
    public function hasTaxonomy(string $taxKey): bool
    {
        try {
            $this->getTaxonomy($taxKey);
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * @return ArrayCollection|Taxonomy[]
     */
    public function getTaxonomies(): ArrayCollection
    {
        $taxonomies = new ArrayCollection;

        foreach ($this->terms as $term) {
            $tax = $term->getTaxonomy();
            if (false === $taxonomies->contains($tax)) {
                $taxonomies->add($tax);
            }
        }

        return $taxonomies;
    }

    /**
     * Returns true if this is an index type entry
     */
    public function isIndex(): bool
    {
        return $this->isIndex;
    }

    public function getEntityName(): string
    {
        return 'entry';
    }

    public function hasTemplate(): bool
    {
        return false === empty($this->getTemplate());
    }
}
