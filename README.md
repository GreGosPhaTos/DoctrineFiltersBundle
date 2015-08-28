# Overview
[![Build Status](https://travis-ci.org/GreGosPhaTos/DoctrineFiltersBundle.svg)](https://travis-ci.org/GreGosPhaTos/DoctrineFiltersBundle) 
[![Latest Stable Version](https://poser.pugx.org/gregosphatos/doctrine-filters-bundle/version)](https://packagist.org/packages/gregosphatos/doctrine-filters-bundle) [![Total Downloads](https://poser.pugx.org/gregosphatos/doctrine-filters-bundle/downloads)](https://packagist.org/packages/gregosphatos/doctrine-filters-bundle) [![Latest Unstable Version](https://poser.pugx.org/gregosphatos/doctrine-filters-bundle/v/unstable)](https://packagist.org/packages/gregosphatos/doctrine-filters-bundle) [![License](https://poser.pugx.org/gregosphatos/doctrine-filters-bundle/license)](https://packagist.org/packages/gregosphatos/doctrine-filters-bundle)


DoctrineFiltersBundle provides integration for [DoctrineFilters](http://doctrine-orm.readthedocs.org/en/latest/reference/filters.html) for your Symfony2 Project.

# Documentation
 
### Record state filter

This filter is really usefull for all db recordset that need to be statued (active, inactive).
Available for Doctrine ORM and Doctrine mongo ODM : 

in your config.yml : 
```yaml
orm:
    entity_managers:
      # Your own entity manager collection
      some_em:
        filters:
          state_filter:
              class:   GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ORM\RecordStateFilter
              enabled: true
```

or

```yaml
doctrine_mongodb:
   document_managers:
    # Your own document manager collection
    some_dm:
      filters:
        state_filter:
          class: GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ODM\RecordStateFilter
          enabled: true

```

Manually add the configurator in your service.yml :

```yaml
services:
  # ORM
  acme.doctrine.orm.filter.configurator:
    class: GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ORM\Configurator
    arguments:
      - "@doctrine.orm.entity_manager"
      - "@annotation_reader"
    tags:
      - { name: kernel.event_listener, event: kernel.request }
  # ODM
  acme.doctrine.odm.filter.configurator:
    class: GreGosPhaTos\DoctrineFiltersBundle\Doctrine\Filter\ODM\Configurator
    arguments:
      - "@doctrine_mongodb.odm.document_manager"
      - "@annotation_reader"
    tags:
      - { name: kernel.event_listener, event: kernel.request }
```

Change your entities or documents : 

```php
/**
 * Entity.
 *
 * @RecordState(stateFieldName="state", activeValue="active")
 */
class MyEntity
{
    /**
     * Record state 
     *  possible values : active, inactive
     */
    private $state;
}
```

Then all the queries will be filter to get only the active records.
