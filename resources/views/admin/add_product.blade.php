@extends('admin_layout')
@section('admin_content')
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				Add More Product
			</header>
			<div class="panel-body">
				<?php 
				$message = Session::get('message');
				if($message){
					echo '<span class="text-alert">',$message,'</span>';
	Session::put('message',null); // neu ton tai bien message thi in ra AdminController
}
?>
<div class="position-center">
	<form role="form" action="{{URL::to('save-product')}}" method="post" enctype="multipart/form-data">

		{{ csrf_field() }} 

		<div class="form-group">
			<label for="exampleInputEmail1">Name Product</label>
			<input type="text" name='product_name' class="form-control" id="exampleInputEmail1" placeholder="Name Product">
		</div>
		<div class="form-group">
			<label for="exampleInputEmail1">Price Product</label>
			<input type="text" name='product_price' class="form-control" id="exampleInputEmail1" >
		</div>
		<div class="form-group">
			<label for="exampleInputEmail1">Image Product</label>
			<input type="file" name='product_image' class="form-control" id="exampleInputEmail1" >
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Description Product</label>

			<textarea style="resize: none" rows="5" type="text" name='product_desc' class="form-control" id="exampleInputPassword1" placeholder="Description"></textarea>

		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Content Product</label>

			<textarea style="resize: none" rows="5" type="text" name='product_content' class="form-control" id="exampleInputPassword1" ></textarea>

		</div>


		<div class="form-group">
			<label for="exampleInputPassword1">Menu Product</label>
			<select name="product_cate" class="form-control input-sm m-bot15">
				@foreach($cate_product as $key => $cate)
				<option value="{{$cate->category_id}}">{{$cate->category_name}}</option>
				@endforeach
				
			</select>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Brand</label>
			<select name="product_brand" class="form-control input-sm m-bot15">

                @foreach($brand_product as $key => $brand)
				<option value="{{$brand->brand_id}}">{{$brand->brand_name}}</option>
				@endforeach

			</select>
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Show/Hide</label>
			<select name="product_status" class="form-control input-sm m-bot15">
				<option value="0">Show</option>
				<option value="1" >Hide</option>
			</select>
		</div>

		<button type="submit" name='add_product' class="btn btn-info">Add</button>
	</form>
</div>

</div>
</section>

</div>
@endsection