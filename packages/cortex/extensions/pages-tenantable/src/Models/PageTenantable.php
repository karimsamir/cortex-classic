<?php

declare(strict_types=1);

namespace Cortex\Pages\Models;

use Rinvex\Tenants\Traits\Tenantable;

class PageTenantable extends Page
{
    use Tenantable;

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable(['tenants']);

        $this->mergeRules(['tenants' => 'nullable|array']);

        parent::__construct($attributes);
    }
}
