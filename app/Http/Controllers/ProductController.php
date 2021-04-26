<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use DB;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	 public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
           return Redirect::to('admin.dashboard');
        }else{
           return Redirect::to('admin')->send();
        }
    }
   	public function add_product(){
   		$this->Authlogin();
   		$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->orderby('brand_id','desc')->get();
   		

   		return view('admin.add_product')->with('cate_product',$cate_product)->with('brand_product',$brand_product);
		

	}
	public function all_product(){
		$this->Authlogin();

		$all_product = DB::table('tbl_product')
		->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
		->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
		->orderby('tbl_product.product_id','desc')->get();
		$manager_product = view('admin.all_product')->with('all_product',$all_product);
		return view('admin_layout')->with('admin.all_product',$manager_product);

	}
	public function save_product(Request $request){
		$this->Authlogin();
		$data = array();
		$data['product_name']= $request->product_name;
		$data['product_price']= $request->product_price;
		$data['product_desc']= $request->product_desc;
		$data['product_content']= $request->product_content;
		$data['category_id']= $request->product_cate;
		$data['brand_id']= $request->product_brand;
		$data['product_status']= $request->product_status;
		$get_image = $request->file('product_image');

		if($get_image){
			$get_name_image = $get_image->getClientOriginalName();
			$name_image = current(explode('.', $get_name_image));
			$new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
			$get_image->move('public/upload/product',$new_image);
			$data['product_image'] = $new_image;
		DB::table('tbl_product')->insert($data);
		Session::put('message','Add product successful');
		return Redirect::to('add-product');

		}

		$data['product_image'] = '';
		DB::table('tbl_product')->insert($data);
		Session::put('message','Add product successful');
		return Redirect::to('add-product');

	}
	public function unactive_product($product_id){
		$this->Authlogin();
		DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>1]);
		Session::put('message','Error when active');
		return Redirect::to('all-product');
	}

	public function active_product($product_id){
		$this->Authlogin();
		DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>0]);
		Session::put('message','Active successful');
		return Redirect::to('all-product');
	}
	public function edit_product($product_id){
		$this->Authlogin();
		$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->orderby('brand_id','desc')->get();
   		
		$edit_product = DB::table('tbl_product')->where('product_id',$product_id)->get();

		$manager_product = view('admin.edit_product')->with('edit_product',$edit_product)->with('cate_product',$cate_product)
		->with('brand_product',$brand_product);

		return view('admin_layout')->with('admin.edit_product',$manager_product);
	}
	public function update_product(Request $request, $product_id){
		$this->Authlogin();
		$data = array();
		$data['product_name']= $request->product_name;
		$data['product_price']= $request->product_price;
		$data['product_desc']= $request->product_desc;
		$data['product_content']= $request->product_content;
		$data['category_id']= $request->product_cate;
		$data['brand_id']= $request->product_brand;
		$data['product_status']= $request->product_status;
		DB::table('tbl_product')->where('product_id',$product_id)->update($data);
		Session::put('message','Update successful');
		return Redirect::to('all-product');
	}
	public function delete_product($product_id){
		$this->Authlogin();
		DB::table('tbl_product')->where('product_id',$product_id)->delete();
		Session::put('message','Delete successful');
		return Redirect::to('all-product');
	}
	//end admin page
	public function details_product($product_id){
		$cate_product = DB::table('tbl_category_product')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->orderby('brand_id','desc')->get();
   		

		$details_product = DB::table('tbl_product')
		->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
		->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
		->where('tbl_product.product_id', $product_id)->get();

		foreach($details_product as $key => $value){
			$category_id = $value->category_id;
		}
		
		$related_product = DB::table('tbl_product')
		->join('tbl_category_product','tbl_category_product.category_id','=','tbl_product.category_id')
		->join('tbl_brand_product','tbl_brand_product.brand_id','=','tbl_product.brand_id')
		->where('tbl_category_product.category_id', $category_id)->get();

		return view('pages.sanpham.show_detail')->with('brand_product',$brand_product)->with('cate_product',$cate_product)->with('details_product',$details_product)->with('related_product',$related_product);
	}

	
}
