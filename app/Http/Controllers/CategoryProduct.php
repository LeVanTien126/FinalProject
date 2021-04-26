<?php

namespace App\Http\Controllers;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use DB;

use Illuminate\Http\Request;






class CategoryProduct extends Controller
{
	public function Authlogin(){
	        $admin_id = Session::get('admin_id');
	        if($admin_id){
	           return Redirect::to('admin.dashboard');
	        }else{
	           return Redirect::to('admin')->send();
	        }
	 }
    		
	public function add_category_product(){
		$this->Authlogin();
		return view('admin.add_category_product');
	}
	public function all_category_product(){
		$this->Authlogin();

		$all_category_product = DB::table('tbl_category_product')->get();
		$manager_category_product = view('admin.all_category_product')->with('all_category_product',$all_category_product);
		return view('admin_layout')->with('admin.all_category_product',$manager_category_product);

	}
	public function save_category_product(Request $request){
		$this->Authlogin();
		$data = array();
		$data['category_name']= $request->category_product_name;
		$data['category_desc']= $request->category_product_desc;
		$data['category_status']= $request->category_product_status;

		DB::table('tbl_category_product')->insert($data);
		Session::put('message','Add product successful');
		return Redirect::to('add-category-product');
	}
	public function unactive_category_product($category_product_id){
		$this->Authlogin();
		DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>1]);
		Session::put('message','Error when active');
		return Redirect::to('all-category-product');
	}

	public function active_category_product($category_product_id){
		$this->Authlogin();
		DB::table('tbl_category_product')->where('category_id',$category_product_id)->update(['category_status'=>0]);
		Session::put('message','Active successful');
		return Redirect::to('all-category-product');
	}
	public function edit_category_product($category_product_id){
		$this->Authlogin();
		$edit_category_product = DB::table('tbl_category_product')->where('category_id',$category_product_id)->get();
		$manager_category_product = view('admin.edit_category_product')->with('edit_category_product',$edit_category_product);
		return view('admin_layout')->with('admin.edit_category_product',$manager_category_product);
	}
	public function update_category_product(Request $request, $category_product_id){
		$this->Authlogin();
		$data = array();
		$data['category_name']= $request->category_product_name;
		$data['category_desc']= $request->category_product_desc;
		DB::table('tbl_category_product')->where('category_id',$category_product_id)->update($data);
		Session::put('message','Update successful');
		return Redirect::to('all-category-product');
	}
	public function delete_category_product($category_product_id){
		$this->Authlogin();
		DB::table('tbl_category_product')->where('category_id',$category_product_id)->delete();
		Session::put('message','Delete successful');
		return Redirect::to('all-category-product');
	}


	//end function adminpage

	public function show_category_home($category_id){
		$cate_product = DB::table('tbl_category_product')->where('category_status','0')->orderby('category_id','desc')->get();
   		$brand_product = DB::table('tbl_brand_product')->where('brand_status','0')->orderby('brand_id','desc')->get();

   		$category_by_id = DB::table('tbl_product')->join('tbl_category_product','tbl_product.category_id','=','tbl_category_product.category_id')->where('tbl_product.category_id',$category_id)->get();
   		$category_name = DB::table('tbl_category_product')->where('tbl_category_product.category_id',$category_id)->limit(1)->get();
		return view('pages.category.show_category',compact('category_by_id'),compact('category_name')
	)->with('category',$cate_product)->with('brand',$brand_product);

	}
	
	//

	 
}
