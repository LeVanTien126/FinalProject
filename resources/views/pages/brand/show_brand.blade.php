@extends('welcome')
@section('content')
@foreach($brand_name as $key => $name)
 <div class="section-header">
 <h1>{{$name->brand_name}}</h1>
 </div>
 @endforeach
<div class="row align-items-center product-slider product-slider-4">
    @foreach($brand_by_id as $key => $product)
        <div class="product-item">
                         
                            <div class="product-title">
                                <a href="#">{{($product->product_name)}}</a>
                                <div class="ratting">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                </div>
                            </div>
                            <div class="product-image">
                                <a href="product-detail.html">
                                    <img src="{{URL::to('public/upload/product/'.$product->product_image)}}" alt="Product Image">
                                </a>
                                <div class="product-action">
                                    <a href="{{URL::to('/details-product/'.$product->product_id)}}"><i class="fa fa-cart-plus"></i></a>
                                    <a href="#"><i class="fa fa-heart"></i></a>
                                    <a href="#"><i class="fa fa-search"></i></a>
                                </div>
                            </div>
                            <div class="product-price">
                                <h3><span>$</span>{{($product->product_price)}}</h3>
                                <a class="btn" href=""><i class="fa fa-shopping-cart"></i>Buy Now</a>
                            </div>
                            
        </div>
    @endforeach 
</div> 
                      
@endsection