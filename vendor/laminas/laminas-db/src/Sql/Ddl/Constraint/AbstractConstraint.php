<?php

namespace Laminas\Db\Sql\Ddl\Constraint;

use function array_fill;
use function array_merge;
use function count;
use function implode;
use function sprintf;

abstract class AbstractConstraint implements ConstraintInterface
{
    /** @var string */
    protected $columnSpecification = ' (%s)';

    /** @var string */
    protected $namedSpecification = 'CONSTRAINT %s ';

    /** @var string */
    protected $specification = '';

    /** @var string */
    protected $name = '';

    /** @var array */
    protected $columns = [];

    /**
     * @param null|string|array $columns
     * @param null|string $name
     */
    public function __construct($columns = null, $name = null)
    {
        if ($columns) {
            $this->setColumns($columns);
        }

        $this->setName($name);
    }

    /**
     * @param  string $name
     * @return self Provides a fluent interface
     */
    public function setName($name)
    {
        $this->name = (string) $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  null|string|array $columns
     * @return self Provides a fluent interface
     */
    public function setColumns($columns)
    {
        $this->columns = (array) $columns;

        return $this;
    }

    /**
     * @param  string $column
     * @return self Provides a fluent interface
     */
    public function addColumn($column)
    {
        $this->columns[] = $column;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritDoc}
     */
    public function getExpressionData()
    {
        $colCount     = count($this->columns);
        $newSpecTypes = [];
        $values       = [];
        $newSpec      = '';

        if ($this->name) {
            $newSpec       .= $this->namedSpecification;
            $values[]       = $this->name;
            $newSpecTypes[] = self::TYPE_IDENTIFIER;
        }

        $newSpec .= $this->specification;

        if ($colCount) {
            $values       = array_merge($values, $this->columns);
            $newSpecParts = array_fill(0, $colCount, '%s');
            $newSpecTypes = array_merge($newSpecTypes, array_fill(0, $colCount, self::TYPE_IDENTIFIER));
            $newSpec     .= sprintf($this->columnSpecification, implode(', ', $newSpecParts));
        }

        return [
            [
                $newSpec,
                $values,
                $newSpecTypes,
            ],
        ];
    }
}
