<?php
namespace Infinitops\Inventory\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model{

    protected $table = 'items';
    
    protected $fillable = ['name', 'description', 'category_id', 'buying_price', 'selling_price', 'upc_code', 'sku_code', 'warn_at'];


    public function getCategory(){
        return Category::find($this->category_id);
    }

}
