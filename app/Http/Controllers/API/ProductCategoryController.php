<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\API\Product\ProductCategoryResource;
use App\Http\Requests\ProductCategoryStoreRequest;
use App\Http\Requests\ProductCategoryUpdateRequest;
use App\Scoping\Scopes\All\ParentScope;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{

  /**
   * List query scope
   *
   * @return Array
   */
    protected function scopes()
    {
        return [
          'parent' => new ParentScope()
        ];
    }

    /**
     * Display a listing of the resource.
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\API\Product\ProductCategoryResource
     */
    public function index(Request $request)
    {
        $categories = ProductCategory::latest()
        ->withScopes(
            $this->scopes()
        );

        if ($request->paginate == null || $request->paginate == 'true') {
            $categories = $categories->paginate(12)
          ->appends(
              $request->except('page')
          );
        } else {
            $categories = $categories->get();
        }

        if ($request->parent == 'true') {
            $categories->load(['childrens']);
        }
        

        return ProductCategoryResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\ProductCategoryStoreRequest $request
     * @return \App\Http\Resources\API\Product\ProductCategoryResource
     */
    public function store(ProductCategoryStoreRequest $request)
    {
        $category = ProductCategory::create(
            $request->only([
              'parent_id',
              'name',
              'slug'
            ])
        );

        return new ProductCategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory $category
     * @return \App\Http\Resources\API\Product\ProductCategoryResource
     */
    public function show(ProductCategory $category)
    {
        $category->load(['childrens']);
        return new ProductCategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\ProductCategoryUpdateRequest $request
     * @param  \App\Models\ProductCategory $category
     * @return \Illuminate\Http\Response
     */
    public function update(ProductCategoryUpdateRequest $request, ProductCategory $category)
    {
        $category->update(
            $request->all()
        );

        return response()->json([
          'data' => __('response.api.updated', [
            'name' => 'product category'
          ])
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Models\ProductCategory $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $category)
    {
        if ($category->children->count() > 0) {
            return response()->json([
              'data' => __('response.api.delete_has_relation', [
                'name' => 'product category'
              ])
            ], 422);
        }

        $category->delete();
        return response()->json([
          'data' => __('response.api.deleted', [
            'name' => 'product category'
          ])
        ], 200);
    }
}
