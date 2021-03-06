<?php

namespace DoctrineProxies\__CG__\Skimpy\CMS;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class ContentItem extends \Skimpy\CMS\ContentItem implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Proxy\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array<string, null> properties to be lazy loaded, indexed by property name
     */
    public static $lazyPropertiesNames = array (
);

    /**
     * @var array<string, mixed> default values of properties to be lazy loaded, with keys being the property names
     *
     * @see \Doctrine\Common\Proxy\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array (
);



    public function __construct(?\Closure $initializer = null, ?\Closure $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return ['__isInitialized__', 'id', 'uri', 'slug', 'title', 'date', 'type', 'content', 'description', 'excerpt', 'seoTitle', 'template', 'isIndex', 'terms', 'metadata'];
        }

        return ['__isInitialized__', 'id', 'uri', 'slug', 'title', 'date', 'type', 'content', 'description', 'excerpt', 'seoTitle', 'template', 'isIndex', 'terms', 'metadata'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (ContentItem $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy::$lazyPropertiesDefaults as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', []);
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', []);
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @deprecated no longer in use - generated code now relies on internal components rather than generated public API
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function __call($method, $args): ?\Doctrine\Common\Collections\ArrayCollection
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__call', [$method, $args]);

        return parent::__call($method, $args);
    }

    /**
     * {@inheritDoc}
     */
    public function getKey(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getKey', []);

        return parent::getKey();
    }

    /**
     * {@inheritDoc}
     */
    public function getId(): ?int
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', []);

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getUri(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUri', []);

        return parent::getUri();
    }

    /**
     * {@inheritDoc}
     */
    public function getSlug(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSlug', []);

        return parent::getSlug();
    }

    /**
     * {@inheritDoc}
     */
    public function getTitle(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTitle', []);

        return parent::getTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function getSeoTitle(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSeoTitle', []);

        return parent::getSeoTitle();
    }

    /**
     * {@inheritDoc}
     */
    public function getDate(): \DateTime
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDate', []);

        return parent::getDate();
    }

    /**
     * {@inheritDoc}
     */
    public function getContent(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContent', []);

        return parent::getContent();
    }

    /**
     * {@inheritDoc}
     */
    public function getExcerpt(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getExcerpt', []);

        return parent::getExcerpt();
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescription', []);

        return parent::getDescription();
    }

    /**
     * {@inheritDoc}
     */
    public function getTemplate(): ?string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTemplate', []);

        return parent::getTemplate();
    }

    /**
     * {@inheritDoc}
     */
    public function getType(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', []);

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function getTerms()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTerms', []);

        return parent::getTerms();
    }

    /**
     * {@inheritDoc}
     */
    public function addTerm(\Skimpy\CMS\Term $term): \Skimpy\CMS\ContentItem
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTerm', [$term]);

        return parent::addTerm($term);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTerm(\Skimpy\CMS\Term $term): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTerm', [$term]);

        return parent::hasTerm($term);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTerm(\Skimpy\CMS\Term $term): \Skimpy\CMS\ContentItem
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTerm', [$term]);

        return parent::removeTerm($term);
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxonomy(string $taxKey): \Skimpy\CMS\Taxonomy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxonomy', [$taxKey]);

        return parent::getTaxonomy($taxKey);
    }

    /**
     * {@inheritDoc}
     */
    public function hasTaxonomy(string $taxKey): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTaxonomy', [$taxKey]);

        return parent::hasTaxonomy($taxKey);
    }

    /**
     * {@inheritDoc}
     */
    public function getTaxonomies()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTaxonomies', []);

        return parent::getTaxonomies();
    }

    /**
     * {@inheritDoc}
     */
    public function isIndex(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'isIndex', []);

        return parent::isIndex();
    }

    /**
     * {@inheritDoc}
     */
    public function getEntityName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEntityName', []);

        return parent::getEntityName();
    }

    /**
     * {@inheritDoc}
     */
    public function hasTemplate(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasTemplate', []);

        return parent::hasTemplate();
    }

    /**
     * {@inheritDoc}
     */
    public function getMetadata(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMetadata', []);

        return parent::getMetadata();
    }

    /**
     * {@inheritDoc}
     */
    public function getMeta(string $key)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMeta', [$key]);

        return parent::getMeta($key);
    }

    /**
     * {@inheritDoc}
     */
    public function setMeta(string $key, $value): \Skimpy\CMS\ContentItem
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMeta', [$key, $value]);

        return parent::setMeta($key, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function removeMeta(string $key)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeMeta', [$key]);

        return parent::removeMeta($key);
    }

}
