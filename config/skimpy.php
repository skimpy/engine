<?php

return [
    # The base URI to access your blog content, it could be /blog or just "/"
    'uri_prefix' => '/',

    # Enable debug
    'debug' => env('APP_DEBUG', false),

    # Unique key to protect rebuilding the DB over HTTP by anyone
    'build_key' => env('BUILD_KEY'),

    # Whether or not to rebuild the database from the blog content
    # on each request. You can probably even leave this on in production
    # if you site is pretty small and you want to keep things super simple.
    'auto_rebuild' => true,

    'site' => [
        'title'   => env('SITE_TITLE', 'Skimpy'),
        'tagline' => env('SITE_TAGLINE', 'Site Tagline'),

        # Used in the author meta tag of the default master layout
        'author' => env('AUTHOR_NAME', 'Your Name'),

        # This can be overridden with frontMatter "description"
        'meta_description' => env('META_DESCRIPTION', 'The default SEO meta description goes here'),

        # The default formatting of dates in twig views.
        # Use the date_default_format twig filter provided
        # by skimpy to format your dates using this default.
        'date_format' => env('PHP_DATE_FORMAT', 'F jS, Y'),

        'timezone' => env('SITE_TIMEZONE', 'UTC'),
    ],
];