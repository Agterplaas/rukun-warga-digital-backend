<?php

namespace App\Traits;

trait MultiTenantModelTrait
{
    public static function bootMultiTenantModelTrait()
    {
        if (! app()->runningInConsole() && auth()->check()) {
            static::creating(function ($model) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
                $model->created_ip = request()->ip();
                $model->updated_ip = request()->ip();
            });
            static::updating(function ($model) {
                $model->updated_by = auth()->id();
                $model->updated_ip = request()->ip();
            });
        }
    }
}
