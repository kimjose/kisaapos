<?php
namespace Infinitops\Inventory\Controllers;

use Infinitops\Inventory\Models\Category;

class CategoriesController extends BaseController{

    public function getCategories(){
        $this->response(200, "Categories", Category::all());
    }

    public function getCategory($id){
        $this->response(200, "Category", Category::find($id));
    }

    public function createCategory($data){
        try {
            $attributes = ['name', 'description'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed"));
            Category::create([
                'name'=> $data['name'], 'description' => $data['description']
            ]);
            $this->getCategories();
        } catch (\Throwable $th) {
            $this->logError($th->getCode(), $th->getMessage());
            $this->response(412, "Unable to create category.");
        }
    }

    public function updateCategory($data, $id){
        try {
            $attributes = ['name', 'description'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed"));
            $category = Category::findOrFail($id);
            $category->name = $data['name'];
            $category->description = $data['description'];
            $category->save();
            $this->getCategories();
        } catch (\Throwable $th) {
            $this->logError($th->getCode(), $th->getMessage());
            $this->response(412, "Unable to update category.");
        }
    }

}
