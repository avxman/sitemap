<?php

return [

    'enabled'=>env('SITEMAP_ENABLED', false),
    'is_multi'=>env('SITEMAP_NULTI', false),
    'model'=>env('SITEMAP_MODEL', ''),
    'folder'=>env('SITEMAP_FOLDER', 'public/sitemap'),
    'chunk'=>env('SITEMAP_EACH_COUNT', 5000),

];
