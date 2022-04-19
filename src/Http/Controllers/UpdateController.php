<?php namespace Phuclh\QuickEdit\Http\Controllers;

use Illuminate\Routing\Controller;
use Laravel\Nova\Http\Requests\NovaRequest;

class UpdateController extends Controller
{
    public function __invoke(NovaRequest $request)
    {
        $request->validate([
            'guideline' => 'required'
        ]);

        $model = $request->model()->find($request->id);
        $model->update([
            'guideline' => $request->guideline,
            'sub_keywords' => $request->subKeywods
        ]);

        //$fieldsRules = [];
        //
        //foreach ($request->newResource()->fields($request) as $field) {
        //    if ($field instanceof QuickEdit) {
        //        foreach ($field->meta['fields'] as $field) {
        //            $fieldsRules[$field->attribute] = is_callable($field->rules)
        //                ? call_user_func($field->rules, $request)
        //                : $field->rules;
        //        }
        //
        //        break;
        //    }
        //}
        //
        //if (!empty($fieldsRules)) {
        //    $validatedData = $request->validate($fieldsRules);
        //}
    }
}
