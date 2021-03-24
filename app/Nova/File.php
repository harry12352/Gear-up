<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class File extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\File::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'path';

    public static function label()
    {
        return 'Media';
    }

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make(__('ID'), 'id')->sortable(),
            $this->check($request),
            $this->setResourceName($request),
            $this->getRelation(),
            Image::make('path')->rules('required'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    public function getResourceName()
    {
        return $this->model()->resource_name;
    }

    public function setResourceName($request){
        if ($request['viaRelationship'] != null) {
            if ($request['viaResource'] == 'users') {
                return Text::make("resource_name")->withMeta(["value" => "user"]);
            } if ($request['viaResource'] === 'products') {
                return Text::make("resource_name")->withMeta(["value" => "product"]);
            } if ($request['viaResource'] === 'brands') {
                return Text::make("resource_name")->withMeta(["value" => "brand"]);
            } if ($request['viaResource'] === 'categories') {
                return Text::make("resource_name")->withMeta(["value" => "category"]);
            } if($request['viaResource'] === 'pages') {
                return Text::make("resource_name")->withMeta(["value" => "page"]);
            }
        }
        else{
            return Text::make('resource_name')->rules('required');
        }
    }

    public function check(Request $request)
    {
        if (empty($request['relationshipType'])) {
            if ($request['viaResource'] === 'users') {
                return BelongsTo::make('resource', 'profileImage', 'App\Nova\User')->onlyOnForms();
            } elseif ($request['viaResource'] === 'products') {
                return BelongsTo::make('resource', 'product', 'App\Nova\Product')->onlyOnForms();
            } elseif ($request['viaResource'] === 'brands') {
                return BelongsTo::make('resource', 'brand', 'App\Nova\Brand')->onlyOnForms();
            } elseif ($request['viaResource'] === 'categories') {
                return BelongsTo::make('resource', 'category', 'App\Nova\Category')->onlyOnForms();
            }
            elseif($request['viaResource'] === 'pages'){
                return BelongsTo::make('resource', 'page', 'App\Nova\Page')->onlyOnForms();
            }
            return Text::make('resource_id')->onlyOnForms()->rules('required');
        }
        if (!empty($request['relationshipType'])) {
            if ($request['viaResource'] == 'users') {
                return BelongsTo::make('resource', 'profileImage', 'App\Nova\User');
            } elseif ($request['viaResource'] === 'products') {
                 return BelongsTo::make('resource', 'product', 'App\Nova\Product');
            } elseif ($request['viaResource'] === 'brands') {
                return BelongsTo::make('resource', 'brand', 'App\Nova\Brand');
            } elseif($request['viaResource'] === 'categories') {
                return BelongsTo::make('resource', 'category', 'App\Nova\Category');
            }
            else{
                return BelongsTo::make('resource', 'page', 'App\Nova\Page');
            }
        }
    }
    public function getRelation()
    {

        if ($this->getResourceName() === 'user') {

            return BelongsTo::make('resource', 'profileImage', 'App\Nova\User')->exceptOnForms();

        } elseif ($this->getResourceName() === 'product') {

             return BelongsTo::make('resource', 'product', 'App\Nova\Product')->exceptOnForms();

        } elseif ($this->getResourceName() === 'brand') {

            return BelongsTo::make('resource', 'brand', 'App\Nova\Brand')->exceptOnForms();

        } elseif($this->getResourceName() === 'category') {
            return BelongsTo::make('resource', 'category', 'App\Nova\Category')->exceptOnForms();
        }
        else{
            return BelongsTo::make('resource', 'page', 'App\Nova\Page')->exceptOnForms();
        }
    }
}
