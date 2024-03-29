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
                محصولات سفارش شماره ({{$orderItem}})</h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title">لیست سفارشات</h3>

                          <div class="card-tools">
                              <div class="input-group input-group-sm" style="width: 150px;">
                                  <input type="text" name="table_search" class="form-control float-right" placeholder="جستجو">

                                  <div class="input-group-append">
                                      <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- /.card-header -->
                      <div class="table table-striped table-valign-middle mb-0">
                          <table class="table table-hover mb-0">
                              <tbody>
                              <tr class="text-center">
                                <th>عنوان</th>
                                <th>دسته بندی</th>
                                <th>لینک دمو</th>
                                <th>لینک دانلود</th>
                                <th>قیمت</th>
                              </tr>
                              @foreach($orders as $order)
                              <tr class="text-center">
                                  <td>{{ $order->product->title }}</td>
                                  <td>{{ $order->product->category->title }}</td>
                                  <td>
                                      <a href="{{ route('product.demo' ,$order->product->id) }}" class="btn btn-default btn-icons" title="لینک دمو"><i class="fa fa-link"></i></a>
                                  </td>
                                  <td>
                                      <a href="{{ route('product.source' , $order->product->id) }}" class="btn btn-default btn-icons" title="لینک دانلود"><i class="fa fa-link"></i></a>
                                  </td>
                                  <td>{{ $order->product->price }}</td>
                              </tr>
                              @endforeach
                              </tbody>
                          </table>
                      </div>
                      <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                  <div class="d-flex justify-content-center">
                      
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



    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>


@endsection