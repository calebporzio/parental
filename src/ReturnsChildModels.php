<?php

namespace Tightenco\Parental;

use Illuminate\Support\Str;

trait ReturnsChildModels
{
    protected $returnsChildModels = true;

    public function newInstance($attributes = [], $exists = false, $connection = null)
    {
        $model = isset($attributes[$this->getInhertanceColumn()])
            ? new $attributes[$this->getInhertanceColumn()]((array) $attributes)
            : new static(((array) $attributes));

        $model->exists = $exists;

        $model->setConnection(
            $connection ?:
            $this->getConnectionName()
        );

        return $model;
    }

    public function newFromBuilder($attributes = [], $connection = null)
    {
        return $this->newInstance((array) $attributes, true, $connection);
    }

    public function belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
    {
        $instance = $this->newRelatedInstance($related);

        if (is_null($foreignKey) && $instance->hasParentModel) {
            $foreignKey = Str::snake($instance->getClassNameForRelationships()).'_'.$instance->getKeyName();
        }

        if (is_null($relation)) {
            $relation = $this->guessBelongsToRelation();
        }

        return parent::belongsTo($related, $foreignKey, $ownerKey, $relation);
    }

    public function hasMany($related, $foreignKey = null, $localKey = null)
    {
        return parent::hasMany($related, $foreignKey = null, $localKey = null);
    }

    // public function belongsToMany($related, $table = null, $foreignKey = null, $relatedKey = null, $relation = null)
    public function belongsToMany($related, $table = null, $foreignPivotKey = null, $relatedPivotKey = null,
                                  $parentKey = null, $relatedKey = null, $relation = null)
    {
        $instance = $this->newRelatedInstance($related);

        if (is_null($table) && $instance->hasParentModel) {
            $table = $this->joiningTable($instance->getClassNameForRelationships());
        }

        return parent::belongsToMany($related, $table, $foreignPivotKey, $relatedPivotKey,
                                     $parentKey, $relatedKey, $relation);
    }

    public function getClassNameForRelationships()
    {
        return class_basename($this);
    }

    public function getInhertanceColumn()
    {
        return $this->inheritanceColumn ?: 'type';
    }
}
