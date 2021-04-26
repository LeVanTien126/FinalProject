@extends('admin_layout')
@section('admin_content')
<div class="row">
	<div class="col-lg-12">
		<section class="panel">
			<header class="panel-heading">
				Add Brand
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
	<form role="form" action="{{URL::to('save-brand-product')}}" method="post">

		{{ csrf_field() }} 

		<div class="form-group">
			<label for="exampleInputEmail1">Name Brand</label>
			<input type="text" name='brand_product_name' class="form-control" id="exampleInputEmail1" placeholder="Name Product">
		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Description Brand</label>

			<textarea style="resize: none" rows="5" type="text" name='brand_product_desc' class="form-control" id="exampleInputPassword1" placeholder="Description"></textarea>

		</div>
		<div class="form-group">
			<label for="exampleInputPassword1">Show/Hide</label>
			<select name="brand_product_status" class="form-control input-sm m-bot15">
				<option value="0">Show</option>
				<option value="1" >Hide</option>
			</select>
		</div>

		<button type="submit" name='add_brand_product' class="btn btn-info">Add brand</button>
	</form>
</div>

</div>
</section>

</div>
@endsection