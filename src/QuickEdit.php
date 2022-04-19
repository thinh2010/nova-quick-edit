<?php

namespace Phuclh\QuickEdit;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class QuickEdit extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'quick-edit';

    public $showOnDetail = false;

    public $showOnCreation = false;

    public $showOnUpdate = false;

    /**
     * @param $guideline
     * @return QuickEdit
     */
    public function guideline($guideline)
    {
        return $this->withMeta(['guideline' => $guideline]);
    }

    /**
     * @param $subKeywords
     * @return QuickEdit
     */
    public function subKeywords($subKeywords)
    {
        return $this->withMeta(['subKeywords' => $subKeywords]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        $this->setResourceId(data_get($resource, $resource->getKeyName()));

        return parent::resolveAttribute($resource, $attribute);
    }

    protected function setResourceId($id)
    {
        return $this->withMeta(['id' => $id]);
    }

    ///**
    // * NovaDependencyContainer constructor.
    // *
    // * @param $fields
    // * @param null $attribute
    // * @param null $resolveCallback
    // */
    //public function __construct($fields, $attribute = null, $resolveCallback = null)
    //{
    //    parent::__construct('', $attribute, $resolveCallback);
    //
    //    $this->withMeta(['fields' => $fields]);
    //}

    ///**
    // * Resolve dependency fields
    // *
    // * @param mixed $resource
    // * @param string $attribute
    // * @return array|mixed
    // */
    //public function resolve($resource, $attribute = null)
    //{
    //    foreach ($this->meta['fields'] as $field) {
    //        $field->resolve($resource, $attribute);
    //    }
    //}
    //
    ///**
    // * Forward fillInto request for each field in this container
    // *
    // * @trace fill/fillForAction -> fillInto -> *
    // *
    // * @param NovaRequest $request
    // * @param $model
    // * @param $attribute
    // * @param null $requestAttribute
    // */
    //public function fillInto(NovaRequest $request, $model, $attribute, $requestAttribute = null)
    //{
    //    foreach($this->meta['fields'] as $field) {
    //        $field->fill($request, $model);
    //    }
    //}
    //
    ///**
    // * Get the validation rules for this field.
    // *
    // * @param NovaRequest $request
    // * @return array
    // */
    //public function getRules(NovaRequest $request)
    //{
    //    $fieldsRules = [$this->attribute => []];
    //
    //    /** @var Field $field */
    //    foreach ($this->meta['fields'] as $field) {
    //        $fieldsRules[$field->attribute] = is_callable($field->{$propertyName})
    //            ? call_user_func($field->{$propertyName}, $request)
    //            : $field->{$propertyName};
    //    }
    //
    //    return $fieldsRules;
    //}
}
