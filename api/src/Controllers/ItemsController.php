<?php
namespace Infinitops\Inventory\Controllers;

use Infinitops\Inventory\Models\Item;

class ItemsController extends BaseController{

    public function getItems() {
        $items = Item::all();
        foreach($items as $item){
            $item['brand'] = $item->getBrand();
            $item['category'] = $item->getCategory();
        }
        $this->response(200, "Items", $items);
    }

    public function getItem($id) {
        $item = Item::find($id);
        if($item != null){
            $item['brand'] = $item->getBrand();
            $item['category'] = $item->getCategory();
        }
        $this->response(200, "Items", $item);
    }
    
    public function createItem($data){
        try {
            $attributes = ['name', 'description', 'brand_id', 'category_id', 'buying_price', 'selling_price', 'upc_code', 'sku_code',];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing object parameters"));
            Item::create($data);
            $this->getItems();
        } catch (\Throwable $th) {
            $this->logError(412, $th->getMessage());
            $this->response(412, "Unable to save item.");
        }
    }

    public function updateItem($data, $id){
        try {
            $attributes = ['name', 'description', 'brand_id', 'category_id', 'buying_price', 'selling_price', 'upc_code', 'sku_code',];
            $missing = Utility::checkMissingAttributes($data, $attributes);
            throw_if(sizeof($missing) > 0, new \Exception("Missing object parameters"));
            $item = Item::findOrFail($id);
            $item->name = $data['name'];
            $item->description = $data['description'];
            $item->category_id = $data['category_id'];
            $item->buying_price = $data['buying_price'];
            $item->selling_price = $data['selling_price'];
            $item->upc_code = $data['upc_code'];
            $item->sku_code = $data['sku_code'];
            $item->brand_id = $data['brand_id'];
            $item->save();
            $this->getItems();
        } catch (\Throwable $th){
            $this->logError(412, $th->getMessage());
            $this->response(412, "Unable to update item.");
        }
    }

}

