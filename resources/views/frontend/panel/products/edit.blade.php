@extends('layouts.admin.master')

@section('content')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2 mt-4">
          <div class="col-12">
            <h1 class="m-0 text-dark">
                <a class="nav-link drawer" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                محصولات / ویرایش
                <a class="btn btn-primary float-left text-white py-2 px-4" href="{{ route('product.list') }}">بازگشت به صفحه محصولات</a>
            </h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
          @include('errors.message')
          <div class="row mt-5">
              <div class="col-md-12">
                  <div class="card card-defualt">
                      <!-- form start -->
                      <form action="{{ route('product.update',$products->id) }}" method="post" enctype="multipart/form-data" >
                          @csrf
                          @method('put')
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>عنوان</label>
                                          <input type="text" class="form-control" name="title" value="{{ $products->title }}" placeholder="نامک را وارد کنید">
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <label>دسته بندی</label>
                                          <select class="form-control" name="category_id">
                                              @foreach ($categories as $category)
                                              <option value="{{ $category->id }}" {{ $category->id == $products->category->id ? 'selected' : '' }} > {{ $category->title }}</option}>
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                                  
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <label>قیمت</label>
                                          <input type="text" value="{{ $products->price }}" class="form-control" name="price" placeholder="قیمت را وارد کنید">
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label>تصویر شاخص</label>
                                          <input class="form-control" value="" type="file" name="thumbnail_url">
                                      </div>
                                      <img src="/{{ $products->thumbnail_url }}" alt="" class="product_img">
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label>تصویر محصول</label>
                                          <input class="form-control" value="{{ $products->demo_url }}" type="file" name="demo_url">
                                      </div>
                                      <img src="/{{ $products->demo_url }}" alt="" class="product_img">
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label>سورس اصلی محصول</label>
                                          <input class="form-control" value="{{ $products->source_url }}" type="file" name="source_url">
                                      </div>
                                      <!-- Download link for source_url -->
                                      <a href="{{ route('product.source', $products->id) }} " class="btn btn-primary float-right">دانلود سورس اصلی </a>
                                  </div>

                              </div>
                              <br>
                              <div class="form-group">
                                  <label>توضیحات</label>
                                  <textarea name="description" value="{{ $products->description }}" id="editor">لطفا متن مورد نظر خودتان را وارد کنید</textarea>
                              </div>
                          </div>
                          <!-- /.card-body -->

                          <div class="card-footer">
                              <button type="submit" class="btn btn-primary float-left"> بروزرسانی </button>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


@endsection