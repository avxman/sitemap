<?php

namespace Avxman\Sitemap\Facades;

use Avxman\Sitemap\Classes\SitemapClass;
use Avxman\Sitemap\Providers\SitemapServiceProvider;

/**
 * Фасад вкл./откл. карта сайта
 *
 * @see SitemapClass
 */
class SitemapFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SitemapServiceProvider::class;
    }

}
