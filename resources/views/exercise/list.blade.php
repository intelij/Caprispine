@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <a title="Daily wise Visit Entry" href="#" data-toggle="modal" data-target="#modal-exercise" class="btn btn-primary" style="float: right;">Add Exercise</a>
            </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($allData)
                      @foreach($allData as $item)
                        <tr>
                          <td>{{$no++}}</td>
                          <td>{{$item->name}}</td>
                          <td>{{strip_tags($item->description)}}</td>
                          <td>{{date("d-M-Y", strtotime($item->created_at))}}</td>
                          <td>
                            @if($item->status == 'active')
                              <small class="label label-success"><a href="{{url('update-exercise-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                            @else
                              <small class="label label-danger"><a href="{{url('update-exercise-status')}}/{{$item->id}}" onclick="return confirm('Are you sure, you want to update status?');" style="color: white;"><i class="fa fa-clock-o"></i> {{$item->status}}</a></small>
                            @endif
                          </td>
                          <td>
                            <a href="#" class="btn btn-primary editExercise" data-toggle="modal" data-target="#modal-edit-exercise" data-id="{{$item->id}}" data-url="{{url('editExercise')}}">Edit</a>
                            <a href="{{url('all-exercise-video')}}/{{$item->id}}" class="btn btn-warning" target="_blank">Add Video</a>
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

<!-- Modal Exercise -->
<div class="modal fade" id="modal-exercise">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Add Exercise</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('save-exercise')}}" id="dynamic_form" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Name</label>
              <div class="col-md-10">
                <input type="text" name="exName" class="form-control" placeholder="Enter Name">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Description</label>
              <div class="col-md-10">
                <textarea class="form-control ckeditor" id="ckeditor" name="description"></textarea>
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

<!-- Edit Modal Exercise -->
<div class="modal fade" id="modal-edit-exercise">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Exercise</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('update-exercise')}}" id="dynamic_form" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Name</label>
              <div class="col-md-10">
                <input type="text" name="exeId" id="exeId" style="display: none;">
                <input type="text" name="exName" class="form-control exName" placeholder="Enter Name">
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Description</label>
              <div class="col-md-10">
                <textarea class="form-control exeDesc ckeditor" id="exeDesc" name="exeDesc"></textarea>
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
<script src="https://cdn.ckeditor.com/4.5.8/standard-all/ckeditor.js"></script>
<script>
  // $(function() {
  // // $('.ckeditor').change(function(){  
  //       CKEDITOR.replace('ckeditor', {
  //           extraPlugins: 'colorbutton,justify,font',
  //           customConfig: '{{ asset('js/config.js') }}',
  //           height: 320,
  //           format_h1 : { element: 'h1', attributes: { 'class': 'test' } },
  //           format_ul : { element: 'ul', attributes: { 'class': 'desc_list' } },
  //           contentsCss : '/exam/asset/css/style.css',
  //           filebrowserImageUploadUrl: '{{PREFIX}}fileUpload?isCk=true',
  //           filebrowserUploadUrl: '{{PREFIX}}fileUploadfile?isCk=true'
  //       });
  //   });  
  // // });  
</script>
@endsection