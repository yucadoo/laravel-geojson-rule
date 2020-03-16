# laravel-geojson-rule

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel Validation Rule for GeoJSON. Built on top of the [GeoJSON PHP Library](https://github.com/jmikola/geojson).
This package is compliant with [PSR-1], [PSR-2], [PSR-4] and [PSR-11]. If you notice compliance oversights,
please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
[PSR-11]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-11-container.md

## Install

Via Composer

``` bash
$ composer require yuca/laravel-geojson-rule
```

## Usage

Create a new `GeoJsonRule` instance without arguments to accept any GeoJSON geometry.

``` php
use Illuminate\Support\Facades\Validator;
use Yuca\LaravelGeoJsonRule\GeoJsonRule;

$validator = Validator::make(
    ['geometry' => '{"type": "Point", "coordinates":[1, 2]}'],
    ['geometry' => new GeoJsonRule()] // Accept any geometry
);
$validator->passes(); // true

$validator = Validator::make(
    ['geometry' => '{"type": "Point", "coordinates":[1]}'],
    ['geometry' => new GeoJsonRule()] // Accept any geometry
);
$validator->passes(); // false
$messages = $validator->messages();
$messages['geometry'][0]; // The geometry does not satisfy the RFC 7946 GeoJSON Format specification because Position requires at least two elements
```

Pass the GeoJson geometry class to limit it.

``` php
use GeoJson\Geometry\Point;
use Illuminate\Support\Facades\Validator;
use Yuca\LaravelGeoJsonRule\GeoJsonRule;

$validator = Validator::make(
    ['position' => '{"type": "Point", "coordinates":[1, 2]}'],
    ['position' => new GeoJsonRule(Point::class)] // Accept Points only
);
$validator->passes(); // true

$validator = Validator::make(
    ['position' => '{"type": "LineString", "coordinates":[[1, 2], [3, 4]]}'],
    ['position' => new GeoJsonRule(Point::class)] // Accept Points only
);
$validator->passes(); // false
$messages = $validator->messages();
echo $messages['position'][0]; // The position does not satisfy the RFC 7946 GeoJSON Format specification for Point.
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email hrcajuka@gmail.com instead of using the issue tracker.

## Credits

- [Hrvoje Jukic][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/yuca/laravel-geojson-rule.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/yuca/laravel-geojson-rule/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/yuca/laravel-geojson-rule.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/yuca/laravel-geojson-rule.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/yuca/laravel-geojson-rule.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/yuca/laravel-geojson-rule
[link-travis]: https://travis-ci.org/yuca/laravel-geojson-rule
[link-scrutinizer]: https://scrutinizer-ci.com/g/yuca/laravel-geojson-rule/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/yuca/laravel-geojson-rule
[link-downloads]: https://packagist.org/packages/yuca/laravel-geojson-rule
[link-author]: https://github.com/yuca
[link-contributors]: ../../contributors
