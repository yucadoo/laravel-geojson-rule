<?php

declare(strict_types=1);

namespace YucaDoo\LaravelGeoJsonRule;

use GeoJson\Geometry\LineString;
use GeoJson\Geometry\MultiLineString;
use GeoJson\Geometry\MultiPoint;
use GeoJson\Geometry\MultiPolygon;
use GeoJson\Geometry\Point;
use GeoJson\Geometry\Polygon;
use PHPUnit\Framework\TestCase;

class GeoJsonRuleTest extends TestCase
{
    /**
     * Test that valid GeoJSON as array passes the rule.
     */
    public function testDecodedJsonPassesRule()
    {
        $rule = new GeoJsonRule();
        $this->assertTrue($rule->passes('test', ['type' => 'Point', 'coordinates' => [1, 2]]));
    }

    /**
     * Test that inalid JSON fails the rule.
     */
    public function testInvalidJsonFailsRule()
    {
        $rule = new GeoJsonRule();
        $this->assertFalse($rule->passes('test', 'Invalid JSON'));

        $message = $rule->message();
        $this->assertStringContainsString('because JSON is invalid', $message);
    }

    /**
     * Test that any valid GeoJSON passes the rule without spcified geometry type.
     * @dataProvider getValidGeoJson
     */
    public function testValidGeoJsonPassesGenericRule(string $encodedJson)
    {
        $rule = new GeoJsonRule();
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that any invalid GeoJSON fails the rule without spcified geometry type.
     * @dataProvider getInvalidGeoJson
     */
    public function testInvalidGeoJsonFailsGenericRule(string $encodedJson)
    {
        $rule = new GeoJsonRule();
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON points pass the rule for points.
     * @dataProvider getValidGeoJsonPoints
     */
    public function testValidPointPassesPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Point::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON points fail the rule for points.
     * @dataProvider getInvalidGeoJsonPoints
     */
    public function testInvalidPointFailsPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Point::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not points fail the rule for points.
     * @dataProvider getValidGeoJsonExceptPoints
     */
    public function testValidNotPointFailsPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Point::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for Point', $message);
    }

    /**
     * Test that valid GeoJSON multi points pass the rule for multi points.
     * @dataProvider getValidGeoJsonMultiPoints
     */
    public function testValidMultiPointPassesMultiPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPoint::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON multi points fail the rule for multi points.
     * @dataProvider getInvalidGeoJsonMultiPoints
     */
    public function testInvalidMultiPointFailsMultiPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPoint::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not multi points fail the rule for multi points.
     * @dataProvider getValidGeoJsonExceptMultiPoints
     */
    public function testValidNotMultiPointFailsMultiPointRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPoint::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for MultiPoint', $message);
    }

    /**
     * Test that valid GeoJSON line strings pass the rule for line strings.
     * @dataProvider getValidGeoJsonLineStrings
     */
    public function testValidLineStringPassesLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(LineString::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON line strings fail the rule for line strings.
     * @dataProvider getInvalidGeoJsonLineStrings
     */
    public function testInvalidLineStringFailsLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(LineString::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not line strings fail the rule for line strings.
     * @dataProvider getValidGeoJsonExceptLineStrings
     */
    public function testValidNotLineStringFailsLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(LineString::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for LineString', $message);
    }

    /**
     * Test that valid GeoJSON multiline strings pass the rule for multiline strings.
     * @dataProvider getValidGeoJsonMultiLineStrings
     */
    public function testValidMultiLineStringPassesMultiLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiLineString::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON multiline strings fail the rule for multiline strings.
     * @dataProvider getInvalidGeoJsonMultiLineStrings
     */
    public function testInvalidMultiLineStringFailsMultiLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiLineString::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not multiline strings fail the rule for multiline strings.
     * @dataProvider getValidGeoJsonExceptMultiLineStrings
     */
    public function testValidNotMultiLineStringFailsMultiLineStringRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiLineString::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for MultiLineString', $message);
    }

    /**
     * Test that valid GeoJSON polygons pass the rule for polygons.
     * @dataProvider getValidGeoJsonPolygons
     */
    public function testValidPolygonPassesPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Polygon::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON polygons fail the rule for polygons.
     * @dataProvider getInvalidGeoJsonPolygons
     */
    public function testInvalidPolygonFailsPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Polygon::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not polygons fail the rule for polygons.
     * @dataProvider getValidGeoJsonExceptPolygons
     */
    public function testValidNotPolygonFailsPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(Polygon::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for Polygon', $message);
    }

    /**
     * Test that valid GeoJSON multi polygons pass the rule for multi polygons.
     * @dataProvider getValidGeoJsonMultiPolygons
     */
    public function testValidMultiPolygonPassesMultiPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPolygon::class);
        $this->assertTrue($rule->passes('test', $encodedJson));
    }

    /**
     * Test that invalid GeoJSON multi polygons fail the rule for multi polygons.
     * @dataProvider getInvalidGeoJsonMultiPolygons
     */
    public function testInvalidMultiPolygonFailsMultiPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPolygon::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('because', $message);
    }

    /**
     * Test that valid GeoJSON geometries which are not multi polygons fail the rule for multi polygons.
     * @dataProvider getValidGeoJsonExceptMultiPolygons
     */
    public function testValidNotMultiPolygonFailsMultiPolygonRule(string $encodedJson)
    {
        $rule = new GeoJsonRule(MultiPolygon::class);
        $this->assertFalse($rule->passes('test', $encodedJson));

        $message = $rule->message();
        $this->assertStringContainsString('for MultiPolygon', $message);
    }

    public function getValidGeoJsonPoints()
    {
        return [
            ['{"type": "Point", "coordinates":[10.5, 15.3]}'],
        ];
    }

    public function getInvalidGeoJsonPoints()
    {
        return [
            // Missing type
            ['{"coordinates":[10.5, 15.3]}'],
            // Invalid coordinates type
            ['{"type": "Point", "coordinates":1}'],
            // Has less than 2 coordinates
            ['{"type": "Point", "coordinates":[1]}'],
        ];
    }

    public function getValidGeoJsonLineStrings()
    {
        return [
            ['{"type": "LineString", "coordinates":[[10.5, 15.3], [51.2, 85.4]]}'],
        ];
    }

    public function getInvalidGeoJsonLineStrings()
    {
        return [
            // Missing type
            ['{"coordinates":[[10.5, 15.3], [51.2, 85.4]]}'],
            // Coordinates isn't array of arrays
            ['{"type": "LineString", "coordinates":[1, 2]}'],
            // Invalid point because it has less than 2 coordinates
            ['{"type": "LineString", "coordinates":[[1], [51.2, 85.4]]}'],
            // Invalid because it has less than 2 points
            ['{"type": "LineString", "coordinates":[[51.2, 85.4]]}'],
        ];
    }

    public function getValidGeoJsonMultiPoints()
    {
        return [
            ['{"type": "MultiPoint", "coordinates":[]}'],
            ['{"type": "MultiPoint", "coordinates":[[10.5, 15.3]]}'],
            ['{"type": "MultiPoint", "coordinates":[[10.5, 15.3], [51.2, 85.4]]}'],
        ];
    }

    public function getInvalidGeoJsonMultiPoints()
    {
        return [
            // Missing type
            ['{"coordinates":[[10.5, 15.3]]}'],
            // Coordinates isn't array of arrays
            ['{"type": "MultiPoint", "coordinates":[1]}'],
            // Invalid point because it has less than 2 coordinates
            ['{"type": "MultiPoint", "coordinates":[[1]]}'],
        ];
    }

    public function getValidGeoJsonMultiLineStrings()
    {
        return [
            ['{"type": "MultiLineString", "coordinates":[]}'],
            ['{"type": "MultiLineString", "coordinates":[[[10.5, 15.3], [51.2, 85.4]]]}'],
            ['{"type": "MultiLineString", "coordinates":[[[10.5, 15.3], [51.2, 85.4]],'.
                '[[1, 2], [2, 2], [1, 3]]]}'],
        ];
    }

    public function getInvalidGeoJsonMultiLineStrings()
    {
        return [
            // Missing type
            ['{"coordinates":[[[10.5, 15.3], [51.2, 85.4]]]}'],
            // Coordinates isn't array of arrays of arrays
            ['{"type": "MultiLineString", "coordinates":[1, 2]}'],
            // Coordinates isn't array of arrays of arrays
            ['{"type": "MultiLineString", "coordinates":[[1, 2], [1, 3]]}'],
            // Invalid point because it has less than 2 coordinates
            ['{"type": "MultiLineString", "coordinates":[[[10.5, 15.3], [85.4]]]}'],
            // Invalid line string because it has less than 4 points
            ['{"type": "MultiLineString", "coordinates":[[[10.5, 15.3]]]}'],
        ];
    }

    public function getValidGeoJsonPolygons()
    {
        return [
            ['{"type": "Polygon", "coordinates":[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]]]}'],
            ['{"type": "Polygon", "coordinates":[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]],' .
                '[[1, 2], [2, 2], [1, 3], [1, 2]]]}'],
        ];
    }

    public function getInvalidGeoJsonPolygons()
    {
        return [
            // Missing type
            ['{"coordinates":[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]]]}'],
            // Coordinates isn't array of arrays of arrays
            ['{"type": "Polygon", "coordinates":[1, 2, 3, 4]}'],
            // Coordinates isn't array of arrays of arrays
            ['{"type": "Polygon", "coordinates":[[1, 2], [1, 3], [2, 2], [1, 2]]}'],
            // Invalid point because it has less than 2 coordinates
            ['{"type": "Polygon", "coordinates":[[[10.5, 15.3], [85.4], [-7.2, -82.4], [10.5, 15.3]]]}'],
            // Invalid because it has less than 4 points
            ['{"type": "Polygon", "coordinates":[[[10.5, 15.3], [51.2, 85.4], [10.5, 15.3]]]}'],
            // Invalid because first and last point are different
            ['{"type": "Polygon", "coordinates":[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [1, 2]]]}'],
        ];
    }

    public function getValidGeoJsonMultiPolygons()
    {
        return [
            ['{"type": "MultiPolygon", "coordinates":[]}'],
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]]]]}'],
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]],' .
                '[[1, 2], [2, 2], [1, 3], [1, 2]]]]}'],
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]]],' .
                '[[[1, 2], [2, 2], [1, 3], [1, 2]]]]}'],
        ];
    }

    public function getInvalidGeoJsonMultiPolygons()
    {
        return [
            // Missing type
            ['{"coordinates":[[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [10.5, 15.3]]]]}'],
            // Coordinates isn't array of arrays of arrays of arrays
            ['{"type": "MultiPolygon", "coordinates":[1, 2, 3, 4]}'],
            // Coordinates isn't array of arrays of arrays of arrays
            ['{"type": "MultiPolygon", "coordinates":[[1, 2, 3, 4]]}'],
            // Coordinates isn't array of arrays of arrays of arrays
            ['{"type": "MultiPolygon", "coordinates":[[[1, 2], [1, 3], [2, 2], [1, 2]]]}'],
            // Invalid point because it has less than 2 coordinates
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [85.4], [-7.2, -82.4], [10.5, 15.3]]]]}'],
            // Invalid because it has less than 4 points
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [51.2, 85.4], [10.5, 15.3]]]]}'],
            // Invalid because first and last point are different
            ['{"type": "MultiPolygon", "coordinates":[[[[10.5, 15.3], [51.2, 85.4], [-7.2, -82.4], [1, 2]]]]}'],
        ];
    }

    public function getValidGeoJson()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonPolygons(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getInvalidGeoJson()
    {
        return array_merge(
            $this->getInvalidGeoJsonPoints(),
            $this->getInvalidGeoJsonMultiPoints(),
            $this->getInvalidGeoJsonLineStrings(),
            $this->getInvalidGeoJsonMultiLineStrings(),
            $this->getInvalidGeoJsonPolygons(),
            $this->getInvalidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptPoints()
    {
        return array_merge(
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonPolygons(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptMultiPoints()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonPolygons(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptLineStrings()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonPolygons(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptMultiLineStrings()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonPolygons(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptPolygons()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonMultiPolygons()
        );
    }

    public function getValidGeoJsonExceptMultiPolygons()
    {
        return array_merge(
            $this->getValidGeoJsonPoints(),
            $this->getValidGeoJsonMultiPoints(),
            $this->getValidGeoJsonLineStrings(),
            $this->getValidGeoJsonMultiLineStrings(),
            $this->getValidGeoJsonPolygons()
        );
    }
}
