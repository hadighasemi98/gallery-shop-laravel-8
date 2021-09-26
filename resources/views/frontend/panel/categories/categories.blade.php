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
                دسته بندی ها
                <a class="btn btn-primary float-left text-white py-2 px-4" href="category/form">افزودن دسته بندی جدید</a>
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
          <div class="row">
              <div class="col-12">
                  <div class="card">
                      <div class="card-header">
                          <h3 class="card-title">لیست دسته بندی ها</h3>

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
                              <tbody><tr>
                                  <th>آیدی</th>
                                  <th>نامک</th>
                                  <th>عنوان</th>
                                  <th>تاریخ ایجاد</th>
                                  <th>عملیات</th>
                              </tr>
                              @foreach($category as $cate)
                                <tr>
                                    <td>{{$cate->id}}         </td>
                                    <td>{{$cate->title}}      </td>
                                    <td>{{$cate->slug}}       </td>
                                    <td>{{$cate->created_at}} </td>
                                    <td>
                                      <form action="{{ route('category.delete',$cate->id) }}" method="post" style="display:inline" >
                                        @csrf
                                        @method('delete')
                                        <button id="deleteBtn" class="btn btn-default btn-icons"><i class="fa fa-trash"></i></button>
                                      </form>
                                        <a href="{{ route('category.edit.form',$cate->id) }}" class="btn btn-default btn-icons"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                              @endforeach
                              
                              </tbody></table>
                      </div>
                      <!-- /.card-body -->
                  </div>
                  <!-- /.card -->
                  <div class="d-flex justify-content-center">
                      <ul class="pagination mt-3">
                        {{ $category->links() }}
                      </ul>
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

<!-- <script type = "text/javascript">
  $(document).ready( function () {
    $('#deleteBtn').click( function(){    
      $.ajax({
        url: '{{ route('category.delete',$cate->id) }}',
        type: 'DELETE',
        data: {movie:movie}, //<-----this should be an object.
        contentType:'application/json',  // <---add this
        dataType: 'text',                // <---update this
        success: function(result) {

        },
        // error: function(result){...}
      });
  });
  }
</script> -->