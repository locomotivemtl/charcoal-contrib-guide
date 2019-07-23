Charcoal Guide
===============

Charcoal contrib guide is a contrib set to add videos (youtube's, at some point vimeo's) as tutorials
in the charcoal-admin interface.

Upon installation, you will get

## Table of Contents

-   [Installation](#installation)
    -   [Dependencies](#dependencies)
-   [Service Provider](#service-provider)
    -   [Parameters](#parameters)
    -   [Services](#services)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Development](#development)
    -  [API Documentation](#api-documentation)
    -  [Development Dependencies](#development-dependencies)
    -  [Coding Style](#coding-style)
-   [Credits](#credits)
-   [License](#license)



## Installation

The preferred (and only supported) method is with Composer:

```shell
$ composer require locomotivemtl/charcoal-contrib-guide
```

### Dependencies

#### Required

-   [**PHP 5.6+**](https://php.net): _PHP 7_ is recommended.
-   [**charcoal-admin**](https://github.com/locomotivemtl/charcoal-admin): >=0.15=


#### PSR

-   [**PSR-7**][psr-7]: Common interface for HTTP messages. Fulfilled by Slim.
-   [**PSR-11**][psr-11]: Common interface for dependency containers. Fulfilled by Pimple.


## Configuration

In your project's config file, require the notification module : 
```json
{
    "modules": {
        "charcoal/admin/guide/guide": {}
    }
}
```

## Usage

Upon installation, you will get a new item in the system menu `Video tutorials` which will bring you to a menu
for the charcoal admin guide interface. To see the scraper interface and the video association interface, you will
need to have superuser access or gain access to `charcoal/admin/guide/edit`. Without the proper access, you will only
be able to see the videos list.

### Scraping youtube videos

Go to `admin/guide/scrape-video`, type in the playlist ID and press `Import`. You be redirected to the admin/guide/video
page with all the newly imported videos, or none if the playlist was not accessible for any reason. Importing videos
WILL overwrite existing videos, you cannot import multiple youtube playlist. **Importing videos will remove all attached
videos that were previously defined in the admin/guide/associate-video page**.

### Associating videos

As of version 0.1.0, you can associate a video to either a `form` or a `table` (widget). Meaning you can only associate
videos to `objects`, not `templates`. The object list comes from the admin menu.

To associate a video, choose a `Widget`, then a `property` when defined (mostly template_ident), then, of course, the
actual `video`. Not defining any property will results as a default behavior, thus applying the video to all entries from
the choosen object. You can then press `save` in the sidebar and that's it. Javascript interpreter will define if a form
or a table has a video attached to it.

#### Important notes
The order in which you define the videos has an impact on the displayed video, as only one video can be assigned to an
object entry.  For example, if you choose to assign a video to a form without defining the property, all subsequent
videos will be avoided.


## Development

To install the development environment:

```shell
$ composer install
```

To run the scripts (phplint, phpcs, and phpunit):

```shell
$ composer test
```

### Development Dependencies

-   [php-coveralls/php-coveralls][phpcov]
-   [phpunit/phpunit][phpunit]
-   [squizlabs/php_codesniffer][phpcs]


## To do list
- Add support for vimeo
- Add support for custom video URL
- Add support for adding videos without overwriting it all
- Add support for templates as opposed to widgets


### Coding Style

The charcoal-contrib-guide module follows the Charcoal coding-style:

-   [_PSR-1_][psr-1]
-   [_PSR-2_][psr-2]
-   [_PSR-4_][psr-4], autoloading is therefore provided by _Composer_.
-   [_phpDocumentor_](http://phpdoc.org/) comments.
-   [phpcs.xml.dist](phpcs.xml.dist) and [.editorconfig](.editorconfig) for coding standards.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.



## Credits

-   [Locomotive](https://locomotive.ca/)


## License

Charcoal is licensed under the MIT license. See [LICENSE](LICENSE) for details.



[charcoal-contrib-guide]:  https://packagist.org/packages/locomotivemtl/charcoal-contrib-guide

[psr-1]:  https://www.php-fig.org/psr/psr-1/
[psr-2]:  https://www.php-fig.org/psr/psr-2/
[psr-3]:  https://www.php-fig.org/psr/psr-3/
[psr-4]:  https://www.php-fig.org/psr/psr-4/
[psr-6]:  https://www.php-fig.org/psr/psr-6/
[psr-7]:  https://www.php-fig.org/psr/psr-7/
[psr-11]: https://www.php-fig.org/psr/psr-11/
