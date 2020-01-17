<?php

namespace DoctrineProxies\__CG__\Skimpy\CMS;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Taxonomy extends \Skimpy\CMS\Taxonomy implements \Doctrine\ORM\Proxy\Proxy
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
            return ['__isInitialized__', 'id', 'uri', 'name', 'pluralName', 'terms', 'config'];
        }

        return ['__isInitialized__', 'id', 'uri', 'name', 'pluralName', 'terms', 'config'];
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Taxonomy $proxy) {
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
    public function getName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getName', []);

        return parent::getName();
    }

    /**
     * {@inheritDoc}
     */
    public function getPluralName(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPluralName', []);

        return parent::getPluralName();
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
    public function addTerm(\Skimpy\CMS\Term $term): \Skimpy\CMS\Taxonomy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addTerm', [$term]);

        return parent::addTerm($term);
    }

    /**
     * {@inheritDoc}
     */
    public function removeTerm(\Skimpy\CMS\Term $term): \Skimpy\CMS\Taxonomy
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeTerm', [$term]);

        return parent::removeTerm($term);
    }

    /**
     * {@inheritDoc}
     */
    public function getEntries(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEntries', []);

        return parent::getEntries();
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
    public function getTermByName(string $termName): ?\Skimpy\CMS\Term
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermByName', [$termName]);

        return parent::getTermByName($termName);
    }

    /**
     * {@inheritDoc}
     */
    public function getTermBySlug(string $termSlug): ?\Skimpy\CMS\Term
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTermBySlug', [$termSlug]);

        return parent::getTermBySlug($termSlug);
    }

    /**
     * {@inheritDoc}
     */
    public function getConfig(): array
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConfig', []);

        return parent::getConfig();
    }

    /**
     * {@inheritDoc}
     */
    public function hasPublicTermsRoute(): bool
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'hasPublicTermsRoute', []);

        return parent::hasPublicTermsRoute();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString(): string
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', []);

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIterator', []);

        return parent::getIterator();
    }

    /**
     * {@inheritDoc}
     */
    public function count(): int
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'count', []);

        return parent::count();
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

}