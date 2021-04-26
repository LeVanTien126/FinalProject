<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use DB;
use Illuminate\Http\Request;
use Cart;

class CheckoutController extends Controller
{
   public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
           return Redirect::to('admin.dashboard');
        }else{
           return Redirect::to('admin')->send();
        }
    }
    public function view_order($orderId){
      $this->Authlogin();

    $order_by_id = DB::table('tbl_order')
    ->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
    ->join('tbl_shipping','tbl_order.shipping_id','=','tbl_shipping.shipping_id')
    ->join('tbl_order_details','tbl_order.order_id','=','tbl_order_details.order_id')
    ->select('tbl_order.*','tbl_customers.*','tbl_shipping.*','tbl_order_details.*')
    ->first();

    $manager_order_by_id = view('admin.view_order')->with('order_by_id',$order_by_id);
    return view('admin_layout')->with('admin.view_order',$manager_order_by_id);
      
    }
    public function login_checkout(){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->where('brand_status','0')->orderby('brand_id','desc')->get();

    	return view('pages.checkout.login_checkout')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function add_customer(Request $request){
    	$data = array();
    	$data['customer_name'] = $request->customer_name; 
    	$data['customer_phone'] = $request->customer_phone; 
    	$data['customer_email'] = $request->customer_email; 
    	$data['customer_password'] = md5($request->customer_password); 

    	$customer_id = DB::table('tbl_customers')->insertGetId($data);

    	Session::put('customer_id',$customer_id);
    	Session::put('customer_name',$request->customer_name);
    	return Redirect('/checkout');
    }
    public function checkout(){
    	$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->where('brand_status','0')->orderby('brand_id','desc')->get();
    	
    	return view('pages.sanpham.checkout')->with('category',$cate_product)->with('brand',$brand_product);
    }
    public function save_checkout_customer(Request $request){
        $data_s = array();
        $data_s['shipping_name'] = $request->shipping_name; 
        $data_s['shipping_phone'] = $request->shipping_phone; 
        $data_s['shipping_email'] = $request->shipping_email; 
        $data_s['shipping_address'] = $request->shipping_address;


        $shipping_id = DB::table('tbl_shipping')->insertGetId($data_s);

        Session::put('shipping_id',$shipping_id);

       //insert payment method
          $data = array();
        $data['payment_method'] = $request->payment_option; 
        $data['payment_status'] = "Waiting"; 
         $payment_id = DB::table('tbl_payment')->insertGetId($data);
       //insert order\
         $order_data = array();
        $order_data['customer_id'] = Session::get('customer_id'); 
        $order_data['shipping_id'] = Session::get('shipping_id'); 
        $order_data['payment_id'] = $payment_id;
        $order_data['order_total'] = Cart::totalFloat(); 
        $order_data['order_status'] = 'Waiting' ;  

         $order_id = DB::table('tbl_order')->insertGetId($order_data);
       //insert order details
         $content = Cart::content();
         $data_insert = array();

         foreach ($content as $v_content){
             # code...
         
        $order_d_data = [];
        $order_d_data['order_id'] = $order_id;
        $order_d_data['product_id'] = $v_content->id; 
        $order_d_data['product_name'] = $v_content->name; 
        $order_d_data['product_price'] = $v_content->price; 
        $order_d_data['product_sales_quantity'] = $v_content->qty;
        $data_insert[] = $order_d_data;
       }
       
       DB::table('tbl_order_details')->insert($data_insert);  

       if ($data['payment_method']==1) {
        Cart::destroy();
        $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','0')->orderby('brand_id','desc')->get();
        
        return view('pages.checkout.handcash')->with('category',$cate_product)->with('brand',$brand_product);
           
       }else {
        echo'Thanh toan bang the ATM';
           
       }
        

    }
    public function payment(){
        $cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
        $brand_product = DB::table('tbl_brand_product')->where('brand_status','0')->orderby('brand_id','desc')->get();

        return view('pages.sanpham.checkout')->with('category',$cate_product)->with('brand',$brand_product);

    }
   
    public function logout_checkout(){
        Session::flush();
        return Redirect('login_checkout');
    }
    public function login_customer(Request $request){
        $email = $request->email_account;
        $password = md5 ($request->password_account);
        $result = DB::table('tbl_customers')->where('customer_email',$email)->where('customer_password',$password)->first();
        if($result){
            return Redirect('/checkout');
        }else{
            return Redirect('/login-checkout');
        }
        Session::put('customer_id',$result->$customer_id);
       
        return Redirect('/checkout');
    }

    public function manage_order(){
      $this->Authlogin();

    $all_order = DB::table('tbl_order')
    ->join('tbl_customers','tbl_order.customer_id','=','tbl_customers.customer_id')
    ->select('tbl_order.*','tbl_customers.customer_name')
    ->orderby('tbl_order.order_id','desc')->get();
    $manager_order = view('admin.manage_order')->with('all_order',$all_order);
    return view('admin_layout')->with('admin.manage_order',$manager_order);

       
      
    }
}
