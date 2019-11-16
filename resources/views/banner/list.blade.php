@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Total Banners: {{count($allData)}}</b></h3>
              <a title="Daily wise Visit Entry" href="#" data-toggle="modal" data-target="#modal-banner" class="btn btn-primary" style="float: right;">Add Banner</a>
            </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Banner</th>
                      <th>Status</th>
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($allData)
                      @foreach($allData as $item)
                        <tr>
                          <td>{{$no++}}</td>
                          <td>@if($item->banner_name) <img src="{{BANNER_IMG.$item->banner_name}}" alt="" width="100" height="50" style="border-radius: 5px;"> @else Not Available @endif</td>
                          <td>
                            @if($item->status == 'active')
                              <small class="label label-success"><a href="{{url('update-banner-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                            @else
                              <small class="label label-danger"><a href="{{url('update-banner-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                            @endif
                            
                          </td>
                          <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
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

<!-- Modal Notification -->
<div class="modal fade" id="modal-banner">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Banner</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('save-banner')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Banner Image</label>
              <div class="col-md-6">
                <input type="file" name="bannerImg" class="form-control">
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-success pull-left">Submit</button>
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          </div>
          <!-- /.box-footer -->
        </form>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection