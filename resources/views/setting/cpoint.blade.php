@extends('layouts.apps')
@section('content')
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <!-- form start -->
            @if(!empty($getData))
              <form class="form-horizontal" method="post" action="{{url('update-cpoint/')}}/{{$getData->id}}">
            @else
              <form class="form-horizontal" method="post" action="{{url('save-cpoint')}}">
            @endif
              {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Name</label>
                  <div class="col-md-3">
                    @if(!empty($getData))
                      <input type="text" class="form-control" name="cname" value="@if(!empty($getData)) {{$getData->name}} @endif" placeholder="Enter Name" readonly>
                    @else
                      <input type="text" class="form-control" name="cname" value="" placeholder="Enter Name" required>
                    @endif
                  </div>
                  <label for="inputEmail3" class="col-md-2 control-label">Capri Point</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="cpoint" value="@if(!empty($getData)) {{$getData->point}} @endif" placeholder="Enter Capri Point" required>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="inputEmail3" class="col-md-2 control-label">Amount</label>
                  <div class="col-md-3">
                    <input type="text" class="form-control" name="camount" value="@if(!empty($getData)) {{$getData->amount}} @endif" placeholder="Enter Amount" required>
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="submit" class="btn btn-info">Submit</button>
                <button type="submit" class="btn btn-default">Cancel</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
          <!-- /.box -->
        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>S.No</th>
                <th>Name</th>
                <th>Point</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @if($allData)
                @foreach($allData as $item)
                  <tr>
                    <td>{{$no++}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->point}}</td>
                    <td>@if($item->amount) {{$item->amount}} @else 0  @endif</td>
                    <td>{{ucfirst($item->status)}}</td>
                    <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                    <td>
                      <a href="{{url('edit-cpoint/')}}/{{$item->id}}" class="btn btn-warning">Edit</a>
                    </td>
                  </tr>
                @endforeach
              @endif
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
        <!-- /.box -->
    </div>
  </div>
</section>

@endsection