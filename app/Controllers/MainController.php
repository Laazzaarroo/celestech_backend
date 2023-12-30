<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Restful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ReportStatus;
use App\Models\Ticket;
use App\Models\Account;
use App\Models\Form;

class MainController extends ResourceController
{
    public $product, $brand, $ticket, $account;

    public function __construct(){
      $this->product = new Product();
      $this->brand = new Brand();
      $this->ticket = new Ticket();
        $this->account = new Account();

    }
    
    public function index(){
      return view('welcome_message');
    }
    public function r_status()
    {
      // $json = $this->request->getJSON();
      $data = [
        'subject' =>$this->request->getVar('subject'),
        'description' => $this->request->getVar('description')
      ];
      $r = new ReportStatus();
      $r->save($data);
      // return $this->respond(['msg'=> 'okay'], 200);
    }
    public function add_product()
    {
      $json = $this->request->getJSON();
      $brand = intval($json->brand);
      $data = [
        'name' => $json->name,
        'description' => $json->description,
        'image' => $json->image,
        'brand_id' => $brand,
        'price' => $json->price,
        'stock' => $json->stock,
      ];
      $save = $this->product->save($data);
      return $this->respond(['message'=>$brand], 200);
    }

    public function getData()
    {
        $data = $this->product->findAll();
        return $this->respond($data, 200);
        // return view('welcome_message');
    }

    public function getCctvList()
    {
        $data['products'] = $this->product->select('*,products.id as prod_id, products.name as model')->join('brand', 'brand.id = products.brand_id')->where('products.status', 'active')->findAll();
        $data['brands'] = $this->brand->findAll();
        $data['unlisted'] = $this->product->select('*,products.id as prod_id, products.name as model')->join('brand', 'brand.id = products.brand_id')->where('products.status', 'inactive')->findAll();
        return $this->respond($data, 200);

    }

    public function getProductData()
    {
      $json = $this->request->getJSON();
      $data = $this->product->where('id', (int)$json->id)->first();
      return $this->respond(['data' => $data], 200);
    }

    public function unlistProduct(){
        $json = $this->request->getJSON();
        $this->product->set('status', 'inactive')->where('id', $json->id)->update();
    }

    public function add_brand()
    {
      $json = $this->request->getJSON();
      $this->brand->save(['name' => $json->brand]);
    }

    //Admin Ticket
    public function getTicketList()
    {
      $data = $this->ticket->select('*,ticket.id as t_id, CONCAT(account.fname," ", account.lname) as name')->join('account', 'account.id = ticket.acc_id')->orderBy('status', 'DESC')->find();
      return $this->respond(['data' => $data], 200);
    }

    public function change_status()
    {
      $json = $this->request->getJSON();
      $this->ticket->set('status', $json->status)->where('id', $json->id)->update();
    }

    //Client Send Report
    public function generateUniqueRandomString($length = 16) {
      $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

      do {
          $randomString = '';
          for ($i = 0; $i < $length; $i++) {
              $randomString .= $characters[rand(0, strlen($characters) - 1)];
          }

          $isUnique = $this->isUniqueInDatabase($randomString);

      } while (!$isUnique);

      return $randomString;
    }

    public function isUniqueInDatabase($randomString) {
        $result = $this->ticket->where('ticket_num', $randomString)->find();

        return count($result) == 0;
    }

    public function sendReport()
    {
      $ticket_num = $this->generateUniqueRandomString();
      $json = $this->request->getJSON();
      $data = [
        'subject' => $json->subject,
        'description' => $json->description,
        'ticket_num' => $ticket_num,
        'acc_id' => $json->id
      ];
      $this->ticket->save($data);

    }

    //Client
    public function getClientList()
    {
      $data = $this->account->select('*, CONCAT(account.fname," ", account.lname) as name, CONCAT(account.barangay,", ", account.city) as address, account.id as id')->join('purchase', 'account.id = purchase.acc_id')->find();
      return $this->respond(['data' => $data], 200);
    }

    //Client purchase
    public function getClientPurchase()
    {
      $json = $this->request->getJSON();
      $data = $this->account->select('*, CONCAT(account.fname," ", account.lname) as name, CONCAT(account.barangay,", ", account.city) as address, account.id as id')->join('purchase', 'account.id = purchase.acc_id')->where('account.id', $json->id)->find();
      return $this->respond(['data' => $data], 200);
    }

    //Sign-In
    public function signIn()
    {
      $json = $this->request->getJSON();
      $data = $this->account->where(['email' => $json->email, 'password' => $json->password])->first();
      if($data){
        return $this->respond(['data' => $data], 200);
      }
      else {
        return $this->respond(['data' => '0'], 200);
      }
    }

    //Products Home
    public function getProductList()
    {
      $data = $this->product->select('*, products.name as model')->join('brand', 'products.brand_id = brand.id')->where('status', 'active')->find();
      return $this->respond(['data' => $data], 200);
    }

    //signup
    public function signup()
    {
      $json = $this->request->getJSON();
      $otp = $this->generateOTP();
      $email = $json->email;
      $data = [
        'email' => $email,
        'password' => $json->password,
        'otp' => $otp,
        'fname' => $json->fname,
        'mname' => $json->mname,
        'lname' => $json->lname,
        'city' => $json->city,
        'barangay' => $json->barangay
      ];
      $this->account->insert($data);
      $id = $this->account->insertID();
      $this->mail($data, $json->email);
      return $this->respond(['id' => $id], 200);
    }

    function generateOTP() {
      $otp = '';
      $characters = '0123456789';
      $length = 5;

      for ($i = 0; $i < $length; $i++) {
          $otp .= $characters[rand(0, strlen($characters) - 1)];
      }

      return $otp;
    }

    //Edit Products
    public function save_edit()
    {
      $json = $this->request->getJSON();
      $data = [
        'name' => $json->data->name,
        'description' => $json->data->description,
        'brand_id' => $json->data->brand_id,
        'price' => $json->data->price,
        'stock' => $json->data->name,

      ];
      if($json->image != ''){
        $data['image'] = $json->image;
      }

      $this->product->set($data)->where('id', $json->id)->update();
      return $this->respond(['msg'=>'updated'], 200);
    }

    //Delete brand
    public function delete_brand()
    {
      $json = $this->request->getJSON();
      $this->brand->where('id', $json->id)->delete();
      $this->product->where('brand_id', $json->id)->delete();
      return $this->respond(['message' => 'deleted']);
    }

    public function restore_prod()
    {
      $json = $this->request->getJSON();
      $this->product->set('status', 'active')->where('id', $json->id)->update();
      return $this->respond(['message' => 'updated']);

    }

    //Generate email
    public function mail($data, $email)
    {
      $email = \Config\Services::email();

      $email->setFrom('secureguard6@gmail.com', 'Celetech Enterprise');
      $email->setTo($data['email']);

      $email->setSubject('Email Verification');
      $email->setMessage(view('email', $data));

      $email->send();
    }

    public function send_otp()
    {
      $json = $this->request->getJSON();
      $update = $this->account->set('verified', 'true')->where(['id' => $json->id, 'otp' => $json->otp])->update();
      if($update){
        return $this->respond(['message' => 'ok'], 200);
      }else{
        return $this->respond(['message' => 'not'], 200);

      }
    }

    public function getAccount()
    {
      $json = $this->request->getJSON();
      $acc = $this->account->select('*, CONCAT(fname, " ", lname) as name')->where('id', $json->id)->first();
      return $this->respond(['name' => $acc['name'], 'email' => $acc['email'], 'address' => $acc['barangay'].', '.$acc['city']], 200);
    }
  public function form_insert()
  {
    $model = new form;
      $json = $this->request->getJSON();
      $data = [
        'name' => $json->data->name,
        'age' => $json->data->age,
        'gender' => $json->data->gender,
      ];
      $this->model->insert($data);
      return $this->respond(['id' => $id], 200);
  }


}
