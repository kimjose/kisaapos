<?php
namespace Infinitops\Inventory\Controllers;

use Infinitops\Inventory\Models\Brand;

class BrandsController extends BaseController{

    public function getBrands(){
        $this->response(200, "Brands", Brand::all());
    }

    public function createBrand($data){
        try{
            $attributes = ['name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed" . json_encode($missing)));
            Brand::create($data);
            $this->getBrands();
        } catch(\Throwable $th){
            $this->logError($th->getCode(), $th->getMessage());
            $this->response(412, $th->getMessage());
            http_response_code(412);
        }
    }

    public function updateBrand($data, $id){
        try {
            $attributes = ['name'];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing parameters passed" . json_encode($missing)));
            $brand = Brand::findOrFail($id);
            $brand->name = $data['name'];
            $brand->save();
            $this->getBrands();
        } catch (\Throwable $th) {
            $this->logError($th->getCode(), $th->getMessage());
            $this->response(412, "Unable to update brand.");
            http_response_code(412);
        }
    }

    public function getBrand($id) {
        $this->response(200, "Brands", Brand::find($id));
    }
    
}
