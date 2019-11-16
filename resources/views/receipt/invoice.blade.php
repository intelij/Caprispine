<a href="#" onclick="printMe()" class="form-control btn btn-primary" style="float: right;">Print</a>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Invoice</title>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
<div class="main-outer" style="width: 98%; margin: auto; overflow-x: hidden;">
    <!-- Navigation -->
    <section class="logo" style="width: 100%;">
    <div class="logo-mg"><img src="{{asset('public/upload/images/logo.png')}}" style="width: 20%;float: left;position: absolute;padding: 8px 0px;/* left: 21%; */"></div>
    <div class="address" style="    text-align: right;float: left;margin-top: 20px;margin-left: 334px;">
    <h2 style=" color: #5d6492; font-style: italic; font-weight: 600; margin-bottom: 8px;">Capri Mobile Physiotherapy Clinic</h2>
    <p style="font-size: 17px; padding: 0; margin: 0; font-weight: 600;">T: +91 9063121212, 9063696969</p>
    <p style="font-size: 17px; padding: 0; margin: 0; font-weight: 600;">E: info@caprispine.com &nbsp;&nbsp;W : www.caprispine.com</p>
    </div>
    </section>
    <nav class="navbar navbar-inverse" role="navigation" style=" float: left; background-color: transparent; border-color: #080808; border-right: 0; border-radius: 0; border-left: 0; border-top: 2px solid #333; border-bottom: 2px solid #333;">
        <div class="container">
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav slip" style="padding: 0;">
                    <li style="padding: 0px 9px 14px 0; font-size: 14px; font-weight: 600; float: left; list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i> 
                      Sant Parmanand Hospital, Civil Lines
                    </li>
                    <li style="padding: 0px 11px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Karkardooma
                    </li>
                    <li style="padding: 0px 12px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Greater Kailash
                    </li>
                    <li style="padding: 0px 13px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Gurgaon
                    </li>
                    <li style="padding: 0px 13px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Pitampura
                    </li>
                    <li style="padding: 0px 0px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Pune
                    </li>
                    <li style="padding: 0px 0px 14px 0; font-size: 14px; font-weight: 600; float: left;  list-style:none;"><i class="fas fa-circle" style="font-size:8px;"></i>
                        Noida
                    </li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <!-- Page Content -->
    <section class="imfo">
    <div class="container pad-none">
     <div class="slip-infomation" style="float: left; width: 100%;">
     <ul style="margin: 0; padding: 5px 0;">
     <li style="display: inline-block; width: 215px; font-size: 18px;"> <b><i> Date :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <?php echo date('d/M/Y'); ?> </i></b> </li>
     <li  style="display: inline-block; width: 327px; font-size: 18px;">Regd. No :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>{{$getData->registration_no}}</i></b> </li>
      <li  style="display: inline-block; padding: 0 0px 0 0; font-size: 18px;">Receipt No. : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><i>{{$getData->id}}</i></b> </li>
     </ul>

     <p style="line-height:28px;">Received with thanks from : <b>{{$getData->name}} </b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs : <b>@if($getData->amount) {{$getData->amount}} /-  ({{convertNumberToWord($getData->amount)}}) @endif by {{ucfirst($getData->payment_type)}} </b><br/>
     Cheque/ Ref. No. : <b>{{$getData->check_or_ref_no}}</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bank : <b>{{$getData->bank}}</b></br>
     on Account of &nbsp;&nbsp;&nbsp;&nbsp;<b> @if($getData->treatment_days) {{$getData->treatment_days}} @else 1 @endif </b>&nbsp;&nbsp;&nbsp;&nbsp;  days <b>‎ Physiotherapy </b> Treatment.</p>

    <ul style="margin: 0; padding: 5px 0;">
     <li  style="display: inline-block; width: 250px; font-size: 18px;"> <b><i> </i></b> </li>
     <li style="display: inline-block; position:absolute;"><img src="{{asset('public/upload/images/logo.png')}}" width="150"></li>
      <li style="display: inline-block;     width: 415px;  text-align: right; font-size:20px; font-weight:600;">Authorised Signatory</li>
     </ul>
     </div>

    </div>
    </section>
    <!-- /.container -->
</div>
</body>
    <script>
        function printMe(){
            window.print();
        }
    </script>
</html>
