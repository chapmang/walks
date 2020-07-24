<?php
namespace App\Domain\DataTypes\Spatial\DBAL\Platform;

use App\Domain\DataTypes\Spatial\Geo\WKB\Parser as BinaryParser;
use App\Domain\DataTypes\Spatial\Geo\WKT\Parser as StringParser;
use App\Domain\DataTypes\Spatial\DBAL\Types\AbstractSpatialType;
use App\Domain\DataTypes\Spatial\DBAL\Types\GeographyType;
use App\Domain\DataTypes\Spatial\Exception\InvalidValueException;
use App\Domain\DataTypes\Spatial\Types\Geometry\GeometryInterface;

/**
 * Abstract spatial platform.
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @author  Alexandre Tranchant <alexandre-tranchant@gmail.com>
 * @license https://dlambert.mit-license.org MIT
 */
abstract class AbstractPlatform implements PlatformInterface
{
    /**
     * Convert binary data to a php value.
     *
     * @param AbstractSpatialType $type    The abstract spatial type
     * @param string              $sqlExpr the SQL expression
     *
     * @throws InvalidValueException when the provided type is not supported
     *
     * @return GeometryInterface
     */
    public function convertBinaryToPhpValue(AbstractSpatialType $type, $sqlExpr)
    {
        $parser = new BinaryParser($sqlExpr);

        return $this->newObjectFromValue($type, $parser->parse());
    }

    /**
     * Convert string data to a php value.
     *
     * @param AbstractSpatialType $type    The abstract spatial type
     * @param string              $sqlExpr the SQL expression
     *
     * @throws InvalidValueException when the provided type is not supported
     *
     * @return GeometryInterface
     */
    public function convertStringToPhpValue(AbstractSpatialType $type, $sqlExpr)
    {
        $parser = new StringParser($sqlExpr);

        return $this->newObjectFromValue($type, $parser->parse());
    }

    // phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter.FoundInImplementedInterfaceBeforeLastUsed

    /**
     * Convert binary data to a php value.
     *
     * @param AbstractSpatialType $type  The spatial type
     * @param GeometryInterface   $value The geometry object
     *
     * @return string
     */
    public function convertToDatabaseValue(AbstractSpatialType $type, GeometryInterface $value)
    {
        //the unused variable $type is used by overriding method
        return sprintf('%s(%s)', mb_strtoupper($value->getType()), $value);
    }

    // phpcs:enable

    /**
     * Get an array of database types that map to this Doctrine type.
     *
     * @param AbstractSpatialType $type the spatial type
     *
     * @return string[]
     */
    public function getMappedDatabaseTypes(AbstractSpatialType $type)
    {
        $sqlType = mb_strtolower($type->getSQLType());

        if ($type instanceof GeographyType && 'geography' !== $sqlType) {
            $sqlType = sprintf('geography(%s)', $sqlType);
        }

        return [$sqlType];
    }

    /**
     * Create spatial object from parsed value.
     *
     * @param AbstractSpatialType $type  The type spatial type
     * @param array               $value The value of the spatial object
     *
     * @throws InvalidValueException when the provided type is not supported
     *
     * @return GeometryInterface
     */
    private function newObjectFromValue(AbstractSpatialType $type, $value)
    {
        $typeFamily = $type->getTypeFamily();
        $typeName = mb_strtoupper($value['type']);

        $constName = sprintf('%s::%s', GeometryInterface::class, $typeName);

        if (!defined($constName)) {
            throw new InvalidValueException(sprintf('Unsupported %s type "%s".', $typeFamily, $typeName));
        }

        $class = sprintf('App\Domain\DataTypes\Spatial\Types\%s\%s', $typeFamily, constant($constName));

        return new $class($value['value'], $value['srid']);
    }
}