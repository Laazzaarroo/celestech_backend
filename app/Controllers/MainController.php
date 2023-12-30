<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Restful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\MainModel;
use App\Models\Product;

class MainController extends ResourceController
{
    public $product;

    public function __construct(){
        $this->product = new Product();
    }

    public function add_product()
    {
      $json = $this->request->getJSON();

      $data = [
        'name' => $json->name,
        'description' => $json->description,
        'image' => $json->image,
        'brand' => $json->brand,
        'price' => $json->price,
        'stock' => $json->stock,
      ];
      $save = $this->product->save($data);
      return $this->respond(['message'=>'success'], 200);
    }

    public function getData()
    {
        // $data = $this->product->findAll();
        // return $this->respond($data, 200);
        return view('welcome_message');
    }

    public function getCctvList()
    {
        $data = $this->product->select('*, products.name as model')->join('brand', 'brand.id = products.brand_id')->where('products.status', 'active')->find();
        return $this->respond($data, 200);

    }

    public function unlistProduct(){
        $json = $this->request->getJSON();
        $this->product->set('status', 'inactive')->where('id', $json->id)->update();
    }
}
