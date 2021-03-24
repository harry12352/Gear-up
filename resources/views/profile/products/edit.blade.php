@extends('layouts.app', ['title' => 'Edit Product :: ' . $user->first_name . ' ' .$user->last_name])
@section('content')
    <div class="container pb-5">
        <form autocomplete="off" id="product-form" action="{{route('products.update', ['user'=>$user->username,'product'=>$product->slug])}}" method="POST">
            @csrf
            <input type="hidden" value="{{$product->id}}" name="product_id" id="product_id">
            <input type="hidden" value="{{route('images.store')}}" name="fileUrl" id="fileUrl">
            <input type="hidden" value="{{ $product->status }}" name="status" id="status">

            <div class="row">
                <div class="col-md-12 product-heading">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <a class="btn btn-back btn-light btn-sm btn-with-icon" href="{{ route('products.index', ['user' => $user->username]) }}">
                                @include('global.partials.icon', ['icon' => 'chevron_left'])
                                Back to Products
                            </a>
                        </div>
                        <div class="col-md-6">
                            <h3 class="mb-1">Edit Product</h3>
                            <p class="small mt-0 mb-3 d-block">Please fill out the fields below to publish your product</p>
                        </div>
                        <div class="col-md-6">
                            <div class="action-buttons float-right mt-2">
                                @if($product->status == "published")
                                    <a href="javascript:void(0)" class="btn btn-outline-secondary choose-draft">Draft Product</a>
                                    <button class="btn btn-primary choose-publish" type="submit">Update</button>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-outline-secondary choose-draft">Update Draft</a>
                                    <button class="btn btn-primary choose-publish" type="submit">Publish</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <hr class="mb-4 d-block">
                </div>
                <div class="col-md-12">
                    @if(!$errors->isEmpty())
                        <div class="alert alert-danger">Some details are missing or invalid, please check errors in fields below</div>
                    @endif
                </div>
                <div class="col-md-12 mt-3">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <h4>Product Information</h4>
                            <p class="info text-muted">Add a title and description to see how this product might appear in a search engine listing</p>
                        </div>
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Title</label>
                                        <input required autofocus type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{old('title', $product->title)}}">
                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea cols="50" row="8" required name="description" class="form-control  @error('description') is-invalid @enderror"> {{old('description', $product->description)}}</textarea>
                                        @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="category">Categories</label>
                                        <select required multiple class="form-control @error('category') is-invalid @enderror" id="category" name="category[]">
                                            @php
                                                $productCategoryIds=[];
                                                if ($product->categories){
                                                    foreach ($product->categories as $category){
                                                        array_push($productCategoryIds, $category['id']);
                                                    }
                                                }
                                            @endphp
                                            @foreach($categories as $category)
                                                @if(in_array($category->id, $productCategoryIds))
                                                    <option selected="selected" value="{{$category->id}}">{{$category->name}}</option>
                                                @else
                                                    <option value="{{$category->id}}">{{$category->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('category')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="brand">Brands</label>
                                        <select required class="form-control @error('brand') is-invalid @enderror" id="brand" name="brand">
                                            <option value="" disabled selected></option>
                                            @foreach($brands as $brand)
                                                <option @if(old('brand', $product->brand->id) == $brand->id) selected @endif value="{{$brand->id}}">{{$brand->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 mt-5">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <h4>Product Details</h4>
                            <p class="info text-muted">Define price for your product, or if you want to put your product on sale, add Sale Price.
                                <br><br>Describe the color and size of your product to help customers find right product quickly</p>
                        </div>
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Price</label>
                                        <input required type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{old('price', $product->price)}}">
                                        @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1">Sale Price</label>
                                        <input type="number" class="form-control @error('sale_price') is-invalid @enderror" id="sale_price" name="sale_price" value="{{old('sale_price',$product->sale_price)}}">
                                        @error('sale_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    @php
                                        $productColorIds=[];
                                        if ($product && count($product->colors) > 0){
                                            foreach ($product->colors as $color){
                                                $productColorIds[] = $color['id'];
                                            }
                                        }
                                    @endphp
                                    <div class="form-group">
                                        <label for="color">Colors</label>
                                        <select multiple class="form-control @error('color') is-invalid @enderror" id="color" name="color[]" required>
                                            @foreach($colors as $color)
                                                @if(in_array($color->id, $productColorIds))
                                                    <option selected="selected" value="{{$color->id}}">{{$color->name}}</option>
                                                @else
                                                    <option value="{{$color->id}}">{{$color->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('color')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="brand">Sizes</label>
                                        <select required class="form-control @error('size') is-invalid @enderror" id="size" name="size">
                                            <option value="" disabled selected></option>
                                            @foreach($sizes as $size)
                                                <option @if($product->size && old('size', $product->size->id) == $size->id) selected @endif value="{{$size->id}}">{{$size->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('brand')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-12 mt-5">
                    <div class="row justify-content-between">
                        <div class="col-md-4">
                            <h4>Product Gallery</h4>
                            <p class="info text-muted">Add best images of your product to make them stand out in crowd. The first image will be used as featured image.
                                <br><br>For the best results, add more than 4 images and have uncluttered background on images</p>
                        </div>
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-group">
                                        <div id="previewImages">
                                            @foreach($productImages as $productImage)
                                                <div class="uploadedImage"><img src="{{$productImage['path']}}">
                                                    <a href="{{ route('images.delete', ['file' => $productImage['id']]) }}" class="remove-image close">&times</a>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="file-upload-zone @error('file') is-invalid @enderror">
                                            <div class="file-upload-image">
                                                @include('global.partials.icon', ['icon' => 'close'])
                                            </div>
                                            <button class="btn btn-light">Upload Images</button>
                                            <br>
                                            <p>or drop images here to upload</p>

                                            <span class="d-block file_error invalid-feedback" role="alert">
                                                <strong></strong>
                                            </span>

                                            <input multiple type="file" class="sr-only form-control-file  @error('file') is-invalid @enderror" id="file" name="file">
                                            @error('file')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
@endsection
