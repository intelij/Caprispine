
<a href="#" onclick="printMe()" class="form-control btn btn-primary" style="float: right;">Print</a>
<!DOCTYPE html>
<html>
<head>
<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META http-equiv="X-UA-Compatible" content="IE=8">
<TITLE>Consent Paper</TITLE>
<META name="generator" content="BCL easyConverter SDK 5.0.140">
</head>
<body>
<div id="page_2" style="width: 100%; height: 692px;margin-left: 0px;margin-top: 10px;padding: 13px 0;">
	<div class="capri1" >
		<P class="p1">
			<img src="{{asset('public/upload/images/logos.png')}}"></P>  <span><h3 class="p2" style="color: red; font-size: 22px;border-top: 1px solid #7395d3;border-bottom: 1px solid #7395d3;text-align: center;margin-top: -99px; margin-left: 172px !important;padding-top: 15px;
			padding-bottom: 15px; float: right;
    width: 74%;"><i style="">CAPRI SPINE CLINIC</i></h3></span>
	</div>
	<div class="capri2">
		<h3 style="margin-top: 95px;text-align: center;font-size: 28px;">CONSENT FORM</h3>

		<p style="font-size: 19px;color:#000;text-align: justify;letter-spacing: 0.2px;margin-bottom: 1em;line-height: 26px;">I, the undersigned by name <b><i>{{$getData->name}}</i></b> age <b><i>{{$getData->age}}</i></b> sex  <b><i>{{ucfirst($getData->gender)}}</i></b>  Self/related to patient as <b><i>{{ucfirst($getData->relation)}}</i></b>  hereby authorise thephysiotherapy and medical staff of Capri Spine Clinicto administer any such physiotherapeutic and medical treatment and its related diagnostic examinations, investigations and procedures which are necessary in the course of treatment at clinic/my home place. I have also understood and have been explained the reasons, advantages and complications of that above treatment and that no guarantee has been made to the outcome obtained. My family and I have also read and understood the clinic tariff, package amount details, rules and regulations. I am ready to pay Rs. <b><i>{{$getData->visitAmount}} / {{$getData->visitDays}} </b></i> visit which may vary in future visits depending on time and treatment delivered.</p>
	</div>

	<div class="capri3" style="float: left; width: 100%;">
		<p style="float: left;width: 20%;">Date: <b style="border-bottom: 1px solid #333;"><i>{{date("d-M-Y", strtotime($getData->created_at))}}</i></b></p>
		<p style="float: left;width: 20%;">Place: <b style="border-bottom: 1px solid #333;"><i>{{$getData->branch}}</i></b> </p>
		<p style="float: left;width: 20%;"> <span style="position: relative;top: -30px;">Signature:</span> @if($getData->patient_sign) <img src="{{SIGNATURE_IMG.$getData->patient_sign}}" width="50" height="50">@endif </p>
		<p style="float: left; width: 20%;">Name: <b style="border-bottom: 1px solid #333;"><i>{{$getData->r_nane}}</i></b> </p></br>
	</div>
	<div class="capri4">
		<p style="float: left; width: 35%;">Name of Therapist: <b style="border-bottom: 1px solid #333;"><i>{{$getData->therapistName}}</i></b></p>
		<p style="float: left; width: 50%;"><span style="position: relative;top: -30px;">Signature of Therapist:</span> @if($getData->therapist_sign) <img src="{{SIGNATURE_IMG.$getData->therapist_sign}}" height="50" width="50"> @endif</p>
	</div>
</div>
<div id="page_1" style="width: 100%; height: 692px;margin-left: 0px; margin-top:220px;">
	<div class="capri1" >
		<P class="p1">
			<img src="{{asset('public/upload/images/logos.png')}}"></P> 
			<span>
			<h3 class="p2" style="color: red; font-size: 22px; border-top: 1px solid #7395d3;border-bottom: 1px solid #7395d3;text-align: center;margin-top: -99px;padding-top: 15px;
			padding-bottom: 15px; float: right; width: 74%;"><i style="">CAPRI SPINE CLINIC</i></h3></span>
	</div>
	<div class="capri2">
		<h3 style="margin-top: 95px;text-align: center;font-size: 28px;">CONSENT FORM </h3>

		<p style="font-size: 19px;color:#000;text-align: justify;letter-spacing: 0.2px;margin-bottom: 1em;line-height: 26px;">मैं, नाम <b><i>{{$getData->name}}</i></b> आयु <b><i>{{$getData->age}}</i></b>  लिंग <b><i>{{ucfirst($getData->gender)}}</i></b> के साथ रोगी से <b><i>{{ucfirst($getData->relation)}}</i></b> संबंधित , इस तरह के किसी  भी
		फिजियोथेरेप्यूटिक और मेडिकल ट्रीटमेंट और उसके संबंधित डायग्नोस्टिक  इंतिहान, जांच और प्रक्रियाओं
		को  करने  के लिए  इस  क्लिनिक  के सभी फिजियोथेरेपी  और  मेडिकल  स्टाफ  को अधिकृत  करता / करती  हु जो 
		उपचार के दौरान आवश्यक हैं। उपरोक्त उपचार के कारणों, फायदों और जटिलताओं को  मुझे समझाया गया है
		तथा मैने भी समझा है एवं  प्राप्त  परिणाम  की कोई गारंटी नहीं दी गयी  है । मेरे परिवार  और मेने क्लिनिक टेरीफ़ / 
पैकेज टेरीफ़ नियमो और विनियमों को भी पढ़ा और समझा है ।
		 में रु <b><i>{{$getData->visitAmount}} / {{$getData->visitDays}} </b></i> भुगतान करने  के लिए तैयार हु जो की समय और उपचार  के आधार पर भविष्य की विजिट भिन्न हो सकता है। </p>
	</div>
	<div class="capri3" style="float: left; width: 100%;">
		<p style="float: left; width: 20%;">दिनांक <b style="border-bottom: 1px solid #333;">:<i>{{date("d-M-Y", strtotime($getData->created_at))}}</i></b> </p>
		<p style="float: left; width: 20%;">स्थान <b style="border-bottom: 1px solid #333;">: <i>{{$getData->branch}}</i></b> </p>
		<p style="float: left; width: 20%;"><span style="position: relative;top: -30px;">हस्ताक्षर</span> @if($getData->patient_sign) <img src="{{SIGNATURE_IMG.$getData->patient_sign}}" width="50" height="50">@endif </p>
		<p style="float: left; width: 20%;">नाम: <b style="border-bottom: 1px solid #333;"><i>{{$getData->r_nane}}</i></b> </p></br>
	</div>
	<div class="capri4">
		<p style="float: left; width: 35%;"> थेरैपिस्ट  का नाम: {{$getData->therapistName}} </p>
		<p style="float: left; width: 50%;"><span style="position: relative;top: -30px;"></span> थेरैपिस्ट  का हस्ताक्षर: @if($getData->therapist_sign) <img src="{{SIGNATURE_IMG.$getData->therapist_sign}}" height="50" width="50"> @endif </p>
	</div>
</div>
	<script>
        function printMe(){
            window.print();
        }
    </script>
</body>
</html>