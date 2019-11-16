@extends('layouts.apps')
@section('content')
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border">
              <h3 class="box-title"><b>Total Notification: {{count($allData)}}</b></h3>
              <!-- <a title="Daily wise Visit Entry" href="#" data-toggle="modal" data-target="#modal-notification" class="btn btn-primary" style="float: right;">Notification</a> -->
              <a title="Send Notification" href="#" data-toggle="modal" data-target="#modal-send-notification" class="btn btn-success" style="float: right;">Send Notification</a>
            </div>
              <div class="box-body">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>S.No</th>
                      <th>Image</th>
                      <th>Therapist / Patient</th>
                      <th>Type</th>
                      <th>Title</th>
                      <th>Description</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @if($allData)
                      @foreach($allData as $item)
                        <tr>
                          <td>{{$no++}}</td>
                          <td>@if($item->image) <img src="{{NOTIFICATION_IMG.$item->image}}" alt="" width="50" height="50" style="border-radius: 100px;"> @else Not Available @endif</td>
                          <td>{{ucfirst($item->flag)}}</td>
                          <td>@if($item->type) {{serviceDetails($item->type)->name}} @endif</td>
                          <td>{{$item->title}}</td>
                          <td>{{$item->message}}</td>
                          <td>{{date("d-M-Y", strtotime($item->date))}}</td>
                          <td>{{$item->time}}</td>
                          <td>
                            <a href="#" data-toggle="modal" data-target="#modal-edit-notification" class="btn btn-warning editNotification" data-id="{{$item->id}}" data-url="{{url('get-notification-details')}}"> Edit</a>
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

<!-- Modal Notification -->
<div class="modal fade" id="modal-notification">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Send Notification</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('save-notification')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Title</label>
              <div class="col-md-4">
                <input type="text" name="title" class="form-control" placeholder="Enter Title">
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Description</label>
              <div class="col-md-4">
                <textarea name="description" class="form-control" placeholder="Enter Description" required></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Date</label>
              <div class="col-md-4">
                <input type="date" name="notificationDate" class="form-control datevalidate" placeholder="Enter Date" required>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Booked Time</label>
              <div class="col-md-4">
                <select class="form-control select2" name="notificationTime">
                  <option selected disabled>Please Select</option>
                  @foreach($allTime as $timeItem)
                  <option value="{{$timeItem->time}}">{{$timeItem->time}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Image</label>
              <div class="col-md-6">
                <input type="file" name="image" class="form-control">
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

<!-- Modal Notification -->
<div class="modal fade" id="modal-send-notification">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Notification</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('save-send-notification')}}" name="myForm" onsubmit="return validate();" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Title</label>
              <div class="col-md-4">
                <input type="text" name="title" class="col-md-2 form-control" placeholder="Enter Title" required>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Message</label>
              <div class="col-md-4">
                <textarea name="description" class="form-control" placeholder="Enter Description" required></textarea>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Therapist / Patient</label>
              <div class="col-md-4">
                <select class="form-control select2" name="type" required>
                  <option value="" selected disabled>Please Select</option>
                  <option value="therapist">All Therapist</option>
                  <option value="patient">All Patient</option>
                </select>
              </div>
              <label for="inputEmail3" class="col-md-2 control-label">Type</label>
              <div class="col-md-4">
                <select class="form-control select2" name="serviceType" required>
                  <option value="" selected disabled>Please Select</option>
                  <option value="6">OPD</option>
                  <option value="7">IPD</option>
                  <option value="9">Home Care</option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Image</label>
              <div class="col-md-4">
                <input type="file" name="image" class="form-control">
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

<!-- Modal of edit notification -->
<div class="modal fade" id="modal-edit-notification">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Edit Notification</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" method="post" action="{{url('update-notifications')}}" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="box-body">
            <div class="form-group row">
              <input type="text" name="notificationId" class="notificationId" value="" style="display: none;">
              <label for="inputEmail3" class="col-md-2 control-label">Title </label>
              <div class="col-md-8">
                <input type="text" name="title" class="form-control notTitle" placeholder="Enter Title" required>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-md-2 control-label">Description </label>
              <div class="col-md-8">
                <textarea name="description" class="form-control notDescription" placeholder="Enter Description"></textarea>
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
<!-- /. modal -->

@endsection