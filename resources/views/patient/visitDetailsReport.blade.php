<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Case Report</title>
    <style>
      table {
        width: 100%;
      }
      table, th, td {
        border: 1px solid black;
        border-collapse:collapse;
      }
      .table
      {
      width:100%;
      text-align:center;}
      h1,h2,h3,h4,h5,h6
      {
        margin-bottom: 12px !important;
        margin-top: 0;}
    </style>
</head>
<body>
  <a href="#" onclick="printMe()" class="form-control btn btn-primary" style="float: right;">Print</a>
<div style='position:relative; overflow: hidden;padding: 0px;border: none; width: 100%;'>
<div  style='position:absolute;top:0px;left:0px;z-index:-1; width: 743px; height: 861px;'>
</div>
<div class="dclr"></div>
<div class="header">
  <!-- <span style='float:left;'><img src="{{asset('upload/images/reportLogo.png')}}" width="350"></span> -->
  <span style='float:left;'><img src="{{asset('public/upload/images/reportLogo.png')}}" width="350"></span>
</div>
<p style='font-family: "Open Sans", sans-serif; margin-bottom: 0;font-size: 18px;line-height: 24px;color: #333;  text-align: center;  padding: 0 14px;
'>
<h3 style="font-size: 37px; margin: 0; text-align: left;     margin-left: 377px; font-style: italic;">Capri Spine Clinic</h3>

<ul style="float:left; width: 41%; margin: 0;">
<li style="text-align: left; float:left; width: 50%; font-weight: 600; ">Karkardooma</li>
<li style="text-align: left; font-weight: 600;">SPH, Civil Lines</li>
<li style="text-align: left; width: 50%; font-weight: 600; 
    float: left;">Greater Kailash</li>
<li style="text-align: left;  font-weight: 600;">Pitampura</li>
<li style=" text-align: left; width: 50%; font-weight: 600; 
    float: left;">Gurgaon</li>
<li style="text-align: left; font-weight: 600;">Noida</li>
<li style=" text-align: left;  font-weight: 600;">Pune</li>
</ul> 
</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 16px; margin: 7px 0 0 24px !important; text-align:  left; float:left; width: 42%; line-height: 16px;color: #333333;padding: 0 14px; margin-top: 0;'>T: 9063696969,9063121212</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 16px;margin: 0px 0 0 24px; text-align: left; float:left; width: 40%; line-height: 16px;color: #333333;padding: 0 14px; margin-top: 0;'>E: info@caprispine.com</p>
<p style='font-family: "Open Sans", sans-serif;font-size: 16px; margin: 0px 0 0 24px; text-align: left; float:left; width: 40%; line-height: 16px;color: #333333;padding: 0 14px;'>www. caprispine.com</p> 
<br>
<p style='font-family: "Open Sans", sans-serif;text-align: center; float:left; font-size: 18px;  width: 100%; font-weight: 800; margin: 7px 0 0 0;'>Patient Details</p>
</div>
  <table class="table table-bordered" style="border-collapse: collapse;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333; padding: 1px 54px;">Name:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->name}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Occupation</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->occupation}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Age:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->age}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Weight:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->width}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Gender:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->gender}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Marital Status:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->marital_status}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Branch:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->branch}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Food Habits:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->food_habit}}</td>
      </tr>

        <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;">Id:</td>
        <td  style="border: 2px solid #333; padding: 1px 54px;">{{$userDetails->registration_no}}</td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;">Height:</td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;">{{$userDetails->height}}</td>
      </tr>
      <tr style="border: 2px solid #333;">
        <td  style="border: 2px solid #333; padding: 1px 54px;"></td>
        <td  style="border: 2px solid #333; padding: 1px 54px;"></td>
        <td  style="border: 2px solid #333; padding: 1px 28px; text-align: center;"></td>
        <td  style="border: 2px solid #333; padding: 1px 32px; text-align: center;"></td>
      </tr>
    </tbody>
  </table>
  @if(count($feedback) > 0)
  <p style='font-family: "Open Sans", sans-serif;text-align: center; font-size: 18px;font-weight: 800;margin: 7px 0 0 0;'>All Feedback</p>
  <table class="table table-bordered" style="border-collapse: collapse;">
    <tbody>
      <tr style="border: 2px solid #ddd;">
        <td style="border: 2px solid #333;  text-align: center;">Date</td>
        <td  style="border: 2px solid #333; text-align: center;">Rating</td>
        <td  style="border: 2px solid #333;  text-align: center;">Comment</td>
        <td  style="border: 2px solid #333;  text-align: center;">Sgnature</td>
      </tr>
      @foreach($feedback as $fdbk)
        <tr style="border: 2px solid #333;">
          <td  style="border: 2px solid #333; text-align: center;">{{date("d-M-Y", strtotime($fdbk->created_at))}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$fdbk->rating}}</td>
          <td  style="border: 2px solid #333; text-align: center;">{{$fdbk->comments}}</td>
          <td  style="border: 2px solid #333;  text-align: center;">
            <img src="{{SIGNATURE_IMG.$fdbk->signature}}" width="30" height="30">
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>
  @endif
  <script>
      function printMe(){
          window.print();
      }
  </script>
</body>
</html>