<?php

declare(strict_types=1);

namespace Yuca\LaravelGeoJsonRule;

use GeoJson\GeoJson;
use GeoJson\Geometry\Polygon;
use Illuminate\Contracts\Validation\Rule;
use Throwable;

/**
 * RFC 7946 GeoJSON Format specification.
 */
class GeoJsonRule implements Rule
{
    /** @var string */
    private $geometryClass;
    /** @var Throwable */
    private $exception;

    /**
     * Constructor.
     * @param string|null $geometryClass Expected geometry.
     */
    public function __construct(?string $geometryClass = null)
    {
        $this->geometryClass = $geometryClass;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        try {
            $value = json_decode($value);
            // An exception will be thrown if parsing fails
            $geometry = GeoJson::jsonUnserialize($value);
        } catch (Throwable $t) {
            $this->exception = $t;
            return false;
        }
        // Check geometry type if specified
        if (!empty($this->geometryClass)) {
            return get_class($geometry) === $this->geometryClass;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        $message = 'The :attribute does not satisfy the RFC 7946 GeoJSON Format specification';
        if (!empty($this->exception)) {
            $message .= ' because ' . $this->exception->getMessage();
        } elseif (!empty($this->geometryClass)) {
            $message .= ' for ' . basename(str_replace('\\', '/', $this->geometryClass));
        }
        return $message;
    }
}
