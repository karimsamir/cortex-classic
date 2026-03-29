<?php

declare(strict_types=1);

namespace Cortex\Auth\Models;

use Rinvex\Tenants\Traits\Tenantable;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class MemberTenantable extends Member
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

    /**
     * Attach the given tenants to the model.
     *
     * @param mixed $tenants
     *
     * @return void
     */
    public function setTenantsAttribute($tenants): void
    {
        static::saved(function (self $model) use ($tenants) {
            $tenants = collect($tenants)->filter();

            $model->tenants->pluck('id')->similar($tenants) || activity()->performedOn($model)->withProperties([
                'attributes' => ['tenants' => $tenants],
                'old' => ['tenants' => $model->tenants->pluck('id')->toArray()],
            ])->log('updated');

            $model->syncTenants($tenants);
        });
    }

    /**
     * Get all attached tenants to the manager.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tenants(): MorphToMany
    {
        return $this->morphToMany(config('rinvex.tenants.models.tenant'), 'tenantable', config('rinvex.tenants.tables.tenantables'), 'tenantable_id', 'tenant_id')->withTimestamps();
    }
}
