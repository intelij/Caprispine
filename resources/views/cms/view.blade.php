@extends('layouts.apps')
@section('content')

<section class="content">
    <div class="row">
        <div class="col-md-12">
          <!-- Horizontal Form -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">{{$title}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            @if($getData)
            	<form class="form-horizontal" method="post" action="{{url('update-cms')}}" enctype="multipart/form-data">
            @else
            	<form class="form-horizontal" method="post" action="{{url('add-cms')}}" enctype="multipart/form-data">
            @endif
            	{{ csrf_field() }}
	              <div class="box-body">
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Privacy & Policy</label>
	              		<div class="col-md-9">
	              			<!-- <textarea id="editor1" name="content" rows="10" cols="80"></textarea> -->
	              			<textarea class="form-control ckeditor" id="ckeditor" name="privacy_policy" required>@if($getData) {{$getData->privacy_policy}} @endif</textarea>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Term & Conditions</label>
	              		<div class="col-md-9">
	              			<!-- <textarea id="editor1" name="content" rows="10" cols="80"></textarea> -->
	              			<textarea class="form-control ckeditor" id="ckeditor" name="term_condition">@if($getData) {{$getData->term_condition}} @endif</textarea>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Rules & Regulations</label>
	              		<div class="col-md-9">
	              			<!-- <textarea id="editor1" name="content" rows="10" cols="80"></textarea> -->
	              			<textarea class="form-control ckeditor" id="ckeditors" name="rule_regulation">@if($getData) {{$getData->rule_regulation}} @endif</textarea>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">About Us</label>
	              		<div class="col-md-9">
	              			<!-- <textarea id="editor1" name="content" rows="10" cols="80"></textarea> -->
	              			<textarea class="form-control ckeditor" id="ckeditors" name="about_us">@if($getData) {{$getData->about_us}} @endif</textarea>
	              		</div>
	              	</div>
	              	<div class="form-group row">
	              		<label for="inputEmail3" class="col-md-3 control-label">Contact Us</label>
	              		<div class="col-md-9">
	              			<!-- <textarea id="editor1" name="content" rows="10" cols="80"></textarea> -->
	              			<textarea class="form-control ckeditor" id="ckeditors" name="contact_us">@if($getData) {{$getData->contact_us}} @endif</textarea>
	              		</div>
	              	</div>
	              	<!-- /.box-body -->
		              <div class="box-footer">
		              	@if($getData)
		              		<button type="submit" class="btn btn-primary">Update</button>
		              	@else
		              		<button type="submit" class="btn btn-primary">Submit</button>
		              	@endif
	                    <a href="{{ URL::previous() }}" class="btn btn-info">Go Back</a>
		              </div>
		            <!-- /.box-footer -->
	              </div>
	          </form>
          	</div>
      	</div>
 	</div>
</section>
<script src="https://cdn.ckeditor.com/4.5.8/standard-all/ckeditor.js"></script>
<script>
  $(function() {
  // $('.ckeditor').change(function(){  
        CKEDITOR.replace('ckeditor', {
            extraPlugins: 'colorbutton,justify,font',
            customConfig: '{{ asset('js/config.js') }}',
            height: 320,
            format_h1 : { element: 'h1', attributes: { 'class': 'test' } },
            format_ul : { element: 'ul', attributes: { 'class': 'desc_list' } },
            contentsCss : '/exam/asset/css/style.css',
            filebrowserImageUploadUrl: '{{PREFIX}}fileUpload?isCk=true',
            filebrowserUploadUrl: '{{PREFIX}}fileUploadfile?isCk=true'
        });
    });  
  // });  
</script>
@endsection