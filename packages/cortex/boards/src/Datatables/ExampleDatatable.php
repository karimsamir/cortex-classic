<?php

declare(strict_types=1);

namespace Cortex\Boards\Datatables;

use Cortex\Boards\\Models\Example;
use Cortex\Boards\\Transformers\ExampleTransformer;
use Cortex\Foundation\DataTables\AbstractDataTable;

class ExampleDatatable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $Example = Example::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = ExampleTransformer::class;

    /**
     * {@inheritdoc}
     */
    protected $exampleTransformer = ExampleTransformer::class;

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            //
        ];
    }
}
