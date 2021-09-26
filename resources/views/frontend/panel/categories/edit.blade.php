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
                دسته بندی ها / ویرایش ({{$category->title}})
                <a class="btn btn-primary float-left text-white py-2 px-4" href="{{ route('category.list') }}">بازگشت به صفحه دسته بندی ها</a>
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
                      <form action="{{ route('category.updated' , $category->id) }} " method="post">
                        @csrf
                        @method('put')
                          <div class="card-body">
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label> نامک جدید</label>
                                          <input value="{{ $category->slug }}" type="text" class="form-control" name="slug" placeholder="نامک را وارد کنید">
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label>عنوان جدید</label>
                                          <input value="{{ $category->title }}" type="text" class="form-control" name="title" placeholder="عنوان را وارد کنید">
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <!-- /.card-body -->

                          <div class="card-footer">
                              <button type="submit" class="btn btn-primary float-left">بروزرسانی </button>
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