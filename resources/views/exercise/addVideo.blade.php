@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
                @php
                    $exName = exerciseDetials($id)->name;
                @endphp
              <h3 class="box-title">Exercise - {{$exName}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal formloc" id="" method="post" action="{{url('add-exercise-video')}}"  enctype="multipart/form-data">
            {{ csrf_field() }}
              <div class="box-body">
                <div class="form-group">
                  <label for="inputEmail3" class="col-sm-2 control-label">Video</label>
                  <div class="col-md-6">
                    <input type="file" class="form-control" name="exVideo" required>
                    <input type="text" name="exerciseId" value="{{$id}}" style="display: none;">
                  </div>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-sm-2 control-label">Exercise Description</label>
                    <div class="col-md-6">
                        <textarea name="videoDesc" class="form-control ckeditor" placeholder="Enter Video Description"></textarea>
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

          <div class="box">
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Video</th>
                      <th>Description</th>
                      <th>Created At</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if($getData)
                        @foreach($getData as $item)
                            <tr>
                              <td>{{$no++}}</td>
                              <td>@if($item->video) <a href="{{EXERCISE_VIDEO.$item->video}}" target="_blank">View</a> @else - @endif</td>
                              <td>{{strip_tags($item->description)}}</td>
                              <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                              <td>
                                @if($item->status == 'active')
                                  <small class="label label-success"><a href="{{url('update-exercise-video-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                                @else
                                  <small class="label label-danger"><a href="{{url('update-exercise-video-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                                @endif
                              </td>
                              <td>
                                <a href="#" class="btn btn-warning editExerciseVideo" data-toggle="modal" data-target="#modal-edit-exercise-video" data-id="{{$item->id}}" data-url="{{url('editExerciseVideos')}}">Edit</a>
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
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->

<!-- Edit Modal Exercise -->
<div class="modal fade" id="modal-edit-exercise-video">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Exercise Video</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('update-exercise-video')}}" id="dynamic_form" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 control-label">Exercise Description</label>
              <div class="col-md-10">
                <textarea name="videoDesc" class="form-control videoDesc ckeditor" id="videoDesc" placeholder="Enter Video Description"></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-2 control-label">Video</label>
              <div class="col-md-6">
                <input type="file" class="form-control" name="exVideo">
                <input type="text" name="oldVideoData" id="oldVideoData" value="" style="display: none;">
                <input type="text" name="exerciseId" value="{{$id}}" style="display: none;">
              </div>
            </div>
            <div class="form-group row videoExe">
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
<script src="https://cdn.ckeditor.com/4.5.8/standard-all/ckeditor.js"></script>
@endsection