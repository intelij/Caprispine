$(document).ready(function(){
    $('.print-button').on('click', function() {  
      window.print();
    });

    // $('.double-scroll').doubleScroll();
    // $('#example1').doubleScroll({resetOnWindowResize: true});
    
    $('#example1').DataTable({
        dom: 'Bfrtip',
        "sScrollX": "100%",
        "sScrollXInner": "110%",
        "bScrollCollapse": true,
        buttons: [
            'excel', 'print'
        ]
    });

    $(".state").change(function (){
        var stateID = $(this).val();
        var url = $(this).data('url');
        if(stateID) {
            $.ajax({
                url: url+'/'+stateID,
                type: "GET",
                dataType: "json",
                success:function(data){
                    $('select[name="city"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="city"]').append('<option value="'+ value['id'] +'">'+ value['name'] +'</option>');
                    });
                }
            });
        }else{
            $('select[name="city"]').empty();
        }
    });

    $('#example1').on('click', '.editNotification', function(){
        var id = $(this).data('id');
        var url = $(this).data('url');
        $('.notificationId').val(id);
        if(id){
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    console.log(data);
                    $('.notTitle').val(data.title);
                    $('.notDescription').val(data.message);
                }
            });
        }
    });

    $('#example1').on('click','.editExercise', function(){
        var id = $(this).data('id');
        var url = $(this).data('url');
        $('#exeId').val(id);
        if(id){
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    $('.exName').val(data.name);
                    CKEDITOR.instances['exeDesc'].setData(data.description);
                }
            });
        }
    });

    $('#example1').on('click','.editExerciseVideo', function(){
        var id = $(this).data('id');
        var url = $(this).data('url');
        $('#exeId').val(id);
        if(id){
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    console.log(data);
                    $('#oldVideoData').val(data.video);
                    CKEDITOR.instances['videoDesc'].setData(data.description); //for ckeditor textarea display text
                    // display video
                    var base_url = '{!! url('/') !!}';
                    var name1 = base_url+'public/upload/exercise_video/'+data.video;

                    $('#editVideo video source').attr('src',name1);
                    $("#editVideo video")[0].load();
                }
            });
        }
    });

    $('.therapistService').on('change', function() {
        var therapistService = $('.therapistService option:selected').val();
        if(therapistService == 9){
            $('.textBox1').css("display", "block");
        }else{
            $('.textBox1').css("display", "none");
        }
    });

    $(".usertype").on('change', function (){
        var usertype = $(this).val();
        var url = $(this).data('url');
        if(usertype){
            $.ajax({
                url: url+'/'+usertype,
                type: "GET",
                dataType: "json",
                success:function(data){
                    $('select[name="userId"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="userId"]').append('<option value="'+ value['id'] +'">'+ value['name'] +'</option>');
                    });
                }
            });
        }else{
            $('select[name="userId"]').empty();
        }
    });

    $(".approvePendingWalletRequest").on('click', function(){
        var id = $(this).data('id');
        var url = $(this).data('url');
        $.ajax({
            url: url+'/'+id,
            type: "GET",
            dataType: "json",
            success:function(data){
                $('#'+id).hide();
                $('.amtType'+id).html('Debit');
                $('.bellImg'+id).hide();
            }
        });
    });

    $(".cancelAppointment").on('click', function(){
        var appId = $(this).data('id');
        var url = $(this).data('url');
        if(appId){
            $.ajax({
                url: url+'/'+appId,
                type: "GET",
                dataType: "json",
                success:function(data){
                    if(data == true){
                        document.getElementById('appStatus'+appId).innerHTML = "Cancel";
                        $('#appStatus'+appId).css("color", "red");
                        $('.ajaxApp'+appId).hide();
                        return true;
                    }else{
                        return false;
                    }
                }
            });
        }else{
            return false;
        }
    });

    $('.packageReferenceNo').change(function() {
        var refNo = $(this).val();
        var url = $(this).data('url');
        if(refNo){
            $.ajax({
                url: url+'/'+refNo,
                type: "GET",
                dataType: "json",
                success:function(data){
                    if(data){
                        $(".packageReferenceNo").val('');
                        return false;
                    }else{
                        return true;
                    }
                }
            });
        }
    });

    $('.payment_type').change(function(){
        var name = $(this).val();
        // alert(serviceId);return false;
        if(name == 'package_wise'){
            $('.packageInvoice').css('display','block');
        }else{
            $('.packageInvoice').css('display','none');
        }
    });

    $('.checkInvoiceForPerDay').on('click', function() {
        var appId = $(this).data('id');
        var url = $(this).data('url');
        if(appId){
            $.ajax({
                url: url+'/'+appId,
                type:'GET',
                dataType: 'json',
                success:function(data){
                }
            });
        }
        return false;
    });

    $('.myFormReport').on('change', function() {
        var patientId = $(this).val();
        $('.selectedPatientId').val(patientId);
        $('.patientReport').css('display','block');
    });

    $('.payment_type').change(function() {
        var serviceId = $(this).data('sid');
        var appId = $(this).data('id');
        var payment_type = $(this).val();
        var urls = $(this).data('urls');
        var url = $(this).data('url');
        if(payment_type == 'per_day_visit'){
            $('.per_day_visit_name').css("display", "none");
        }else{
            $('.per_day_visit_name').css("display", "block");
        }
        if(appId){
            $.ajax({
                url: urls+'/'+appId,
                type: "GET",
                dataType: "json",
                success:function(data){
                    if(data == 'notavailable'){
                        swal({
                          title: 'Therapist not assigned!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('.payment_type').val('');
                          return false;
                        });
                    }else if(data == 'true'){
                        if(payment_type){
                            $.ajax({
                                url: url+'/'+serviceId,
                                type: "GET",
                                dataType: "json",
                                success:function(datas){
                                    $('select[name="package_type"]').empty();
                                    $.each(datas, function(key, value) {
                                        if(payment_type == 'package_wise'){
                                            $('select[name="package_type"]').append('<option value="'+value['id'] +'">'+value['name']+' ( '+value['package_amount']+'/- with '+value['commission']+'% for '+value['joints']+' )'+'</option>');
                                        }else if(payment_type == 'per_day_visit'){
                                            $('select[name="package_type"]').append('<option value="'+value['id'] +'">'+value['name']+' ( '+value['per_amount']+'/- with '+value['days']+' Days with ' +value['commission']+'% for '+value['joints']+' )'+'</option>');
                                        }
                                    });
                                }
                            });
                        }else{
                            $('select[name="package_type"]').empty();
                        }
                    }else{
                        swal({
                          title: 'Today, Therapist not Present!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('.payment_type').val('');
                          return false;
                        });
                    }
                }
            });
        }
    });

    $('.package_type_check_condition').change(function(){
        var urls = $(this).data('urls');
        var appId = $(this).data('id');
        var url = $(this).data('url');
        if(appId){
            $.ajax({
                url: url+'/'+appId,
                type: "GET",
                dataType: "json",
                success:function(data){
                    if(data == 'true'){
                        if(urls){
                            $.ajax({
                                url: urls+'/'+appId,
                                type: "GET",
                                dataType: "json",
                                success:function(datas){
                                    if(datas == 'false'){
                                        swal({
                                          title: 'Firstly Paid Package Amount and generate Invoice!!',
                                          text: "",
                                          type: 'warning',
                                          showCancelButton: true,
                                          confirmButtonColor: '#3085d6',
                                          cancelButtonColor: '#d33',
                                          confirmButtonText: 'Okay!'
                                        }).then((result) => {
                                          $('.package_type_check_condition').val('');
                                          return false;
                                        });
                                    }else{
                                        return true;
                                    }
                                }
                            });
                        }
                    }else if(data == 'notavailable'){
                        swal({
                          title: 'Therapist not assigned!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('.package_type_check_condition').val('');
                          return false;
                        });
                    }else{
                        swal({
                          title: 'Today, Therapist not Present!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('.package_type_check_condition').val('');
                          return false;
                        });
                    }
                }
            });
        }
    });

    $('.jointSelected').change(function() {
        var jointName = $(this).val();
        var paymentType = $('.payment_type').val();
        var url = $(this).data('url');
        if((paymentType != null) && (paymentType === 'package_wise')){
            if(jointName){
                $.ajax({
                    url: url+'/'+jointName,
                    type: "GET",
                    dataType: "json",
                    success:function(data){
                        console.log(data);
                        $('select[name="package_type"]').empty();
                        $.each(data, function(key, value) {
                            $('select[name="package_type"]').append('<option value="'+ value['id'] +'">'+ value['name']+' ( '+value['package_amount']+'/- with ' +value['commission']+'% for '+value['joints']+' )'+'</option>');
                        });
                    }
                });
            }else{
                $('select[name="package_type"]').empty();
            }
        }else{
            $('select[name="package_type"]').empty();
        }
    });

    $('.treatmentType').change(function() {
        var url = $(this).data('url');
        var treatmentType = $(this).val();
        if(treatmentType == 'perday'){
            var flag = 'perday';
        }else{
            var flag = 'package';
        }

        if(treatmentType){
            $.ajax({
                url: url+'/'+flag,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    $('select[name="registration_no"]').empty();
                    $.each(data, function(key, value) {
                        $('select[name="registration_no"]').append('<option value="'+ value['id'] +'">'+ value['name'] + ' ('+value['registration_no']+') ('+ value['appointment_date'] +')'+'</option>');
                    });
                }
            });
        }else{
            $('select[name="registration_no"]').empty();
        }
    });

    $(".module_name").change(function (){
        var moduleId = $(this).val();
        var url = $(this).data('url');
        if(moduleId){
            $.ajax({
                url: url+'/'+moduleId,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    $('select[id="sub_module_name"]').empty();
                    $.each(data, function(key, value) {
                        $('select[id="sub_module_name"]').append('<option value="'+ value['id'] +'">'+ value['name'] +'</option>');
                    });
                }
            })
        }else{
            $('select[id="sub_module_name"]').empty();
        }
    });

    $('.checkValidBookedTime').on('change', function(){
        var bookedTime = $('.checkBookedAppointmentTime option:selected').val();
        var bookedDate = $('.booked_date').val();
        if((bookedDate != '') && (bookedTime != '')){
            var newtherapistId = $('.newTherapistId option:selected').val();
            var oldtherapistId = $('.selectedTherapistName').val();
            if(newtherapistId == ''){
                var therapistId = oldtherapistId;
            }else{
                var therapistId = newtherapistId;
            }
            var url = $(this).data('url');
            if(bookedTime){
                $.ajax({
                    url: url+'/'+therapistId+'/'+bookedDate+'/'+bookedTime,
                    type: 'GET',
                    dataType: 'json',
                    success:function(data){
                        if(data == 'true'){
                            return true;
                        }else{
                            alert('Appointment already booked!!');
                            $('.checkBookedAppointmentTime').val('');
                            return false;
                        }
                    }
                });
            }
        }else{
            alert('Please select Booked date and Booked time');
            return false;
        }
    });

    $('.checkBookedAppointmentTime').on('change', function(){
        var bookedTime = $('.checkBookedAppointmentTime option:selected').val();
        var bookedDate = $('.booked_date').val();
        if((bookedDate != '') && (bookedTime != '')){
            var newtherapistId = $('.newTherapistId option:selected').val();
            var oldtherapistId = $('.selectedTherapistName').val();
            if(newtherapistId == ''){
                var therapistId = oldtherapistId;
            }else{
                var therapistId = newtherapistId;
            }
            var url = $(this).data('url');
            if(bookedTime){
                $.ajax({
                    url: url+'/'+therapistId+'/'+bookedDate+'/'+bookedTime,
                    type: 'GET',
                    dataType: 'json',
                    success:function(data){
                        if(data == 'true'){
                            return true;
                        }else{
                            alert('Appointment already booked!!');
                            $('.checkBookedAppointmentTime').val('');
                            return false;
                        }
                    }
                });
            }
        }else{
            alert('Please select Booked date and Booked time');
            return false;
        }
    });

    $('.checkAppointmentTime').on('change', function(){
        var appTime = $('.checkAppointmentTime option:selected').val();
        var appTherapist = $('.appTherapistId option:selected').val();
        var url = $(this).data('url');
        if(appTherapist){
            $.ajax({
                url: url+'/'+appTherapist+'/'+appTime,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    if(data == true){
                        alert('Appointment already booked to therapist with these time slot!!');
                        $("select#appointmentTimeSlot").prop('selectedIndex', 0);
                        $('#appointmentTimeSlot').focus();
                        return false;
                    }else{
                        return true;
                    }
                }
            });
        }else{
            alert('Please select valid Therapist and Appointment Time slots.');
            return false;
        }

    });


    $('.penaltyId').on('change', function(){
        var penaltyId = $(this).val();
        var url = $(this).data('url');
            $('.condition1').css("display", "none");
            if(penaltyId){
                $.ajax({
                    url: url+'/'+penaltyId,
                    type: 'GET',
                    dataType: 'json',
                    success:function(data){
                        $('.penaltyAmt').val(data['amount']);
                    }
                });
            }
    });

    $('.selectedPaymentType').on('change', function() {
        var pamentType = $(this).val();
        if(pamentType != 'cash'){
            $('.reference_check').css("display", "block");
        }else{
            $('.reference_check').css("display", "none");
        }

    });

    $('.patientType').on('change', function() {
        var patientType = $(this).val();
    });

    $('#example1').on('click', '.approvedCPoint', function(){
        var id = $(this).data('id');
        //alert(id);
        $('.cpointId').val(id);
        return true;

    });

    $('.late_coming').on('change', function() {
        var getTime = $(this).val();
        // per minute 10 Rs fine
        var totalPenalty = getTime * 10;
        $('.penaltyAmt').val(totalPenalty);
        return true;
    });

    $('.patientSelected').on('change', function() {
        var patientId = $('.patientSelected option:selected').val();
        var url = $(this).data('url');
        if(patientId){
            $.ajax({
                url: url+'/'+patientId,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    // $('.appTherapistId option[value='.data['therapist_id'].']').attr('selected','selected');
                }
            });
        }
    });

    $('.patientMobileNo').on('change', function(e){
        e.preventDefault();
        var contactNo = $('.patientMobileNo').val();
        var url = $(this).data('url');
        if(contactNo){
            $.ajax({
                url: url+'/'+contactNo,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data == 'false'){
                        // sweetalert
                        swal({
                          title: 'Duplicate Contact No!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('.patientMobileNo').val('');
                          return false;
                        });
                    }else{
                        return true;
                    }
                }
            });
        }
    });

    $('.booked_date').on('change', function(e){
        var url = $(this).data('url');
        var therapistId = $('.selectedTherapistName').val();
        if(therapistId == ''){
            // sweetalert
            swal({
              title: 'Firstly Assign Therapist to this patient!',
              text: "",
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Okay!'
            }).then((result) => {
              $('.booked_date').val('');
              $('.booked_time').empty();
              return false;
            });
        }else{
            return true;
        }
    });
    
    $('.therapistAttCheck').on('change', function(e){
        var url = $(this).data('urls');
        var therapistId = $(this).val();
        $.ajax({
            url: url+'/'+therapistId,
            type: 'GET',
            dataType: 'json',
            success: function(data){
                if(data == 'false'){
                    // sweetalert
                    swal({
                      title: 'Therapist not Present Today!!',
                      text: "",
                      type: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Okay!'
                    }).then((result) => {
                      $('.booked_date').val('');
                      $('.booked_time').empty();
                      $('.therapistAttCheck').val('');
                      return false;
                    });
                }else{
                    return true;
                }
            }
        });
    });

    $('#example1').on('click', '.refundAmount', function(){
        var invoiceId = $(this).data('id');
        $('.invoiceId').val(invoiceId);
        var joint = $(this).data('joint');
        $('.refundJoint').val(joint);
        var url = $(this).data('url');
        if(invoiceId){
            $.ajax({
                url: url+'/'+invoiceId+'/'+joint,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('label[for="totalPackageAmount"]').text(data['packageAmount']);
                    $('label[for="paidPackageAmount"]').text(data['PaidAmount']);

                }
            });
        }
    });

    $('#assignselectedTherapist').on("change", function() {
        var therapistId = $(this).val();
        var url = $(this).data('url');
        if(therapistId){
            $.ajax({
                url: url+'/'+therapistId,
                type: 'GET',
                dataType: 'json',
                success:function(data){
                    if(data == 'true'){
                      return true;
                    }else{
                      swal({
                          title: 'Today Therapist is Apsent!!',
                          text: "",
                          type: 'warning',
                          showCancelButton: true,
                          confirmButtonColor: '#3085d6',
                          cancelButtonColor: '#d33',
                          confirmButtonText: 'Okay!'
                        }).then((result) => {
                          $('#assignselectedTherapist').val('')
                          return false;
                        });
                    }
                }
            });
        }
    });

    $('#check-attendance').on('click',function(e){
    e.preventDefault();
    var url = $(this).data('url');
    var id = $(this).data('id');
    if(id){
      $.ajax({
        url: url+'/'+id,
        type: "GET",
        dataType: "json",
        success:function(data){
            if(data == 'true'){
              return true;
            }else{
              swal({
                  title: 'Today Therapist is Apsent!!',
                  text: "",
                  type: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Okay!'
                }).then((result) => {
                  return false;
                });
            }
        }
      });
    }else{
      return false;
    }
  });

    $('#example1').on('click', '.perDayEditId', function(){
        var id = $(this).data('id');
        var appId = $(this).data('ids');
        var url = $(this).data('url');
        $('.perDayId').val(id);
        $('.appointmentId').val(appId);
        if(id){
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    if(data['amount'] = 0 || data['amount'] == false || data['amount'] == null){
                        $('.amountCheck').css("display", "block");
                    }else{
                        $('.amountCheck').css("display", "none");
                    }
                    $('.booked_date').val(data['app_booked_date']);
                    $('.booked_time').val(data['app_booked_time']);
                    $('.inTime').val(data['in_time']);
                    $('.outTime').val(data['out_time']);
                    // $('select[name="extraAmt"] option[value="'+data['extra_amount']+'"]').attr("selected","selected");
                    // $('.extraAmt').children("option:selected").val();
                    // $("#gender").val("Male").attr('selected','selected);
                    $('.therapist_id').val(data['therapist_id']);
                }
            });
        }
        return true;
    });

    $('#example1').on('click', '.packageEditId', function(){
        var id = $(this).data('id');
        $('.packageDayId').val(id);
        var url = $(this).data('url');
        if(id){
            $.ajax({
                url: url+'/'+id,
                type: 'GET',
                dataType: 'json',
                success: function(data){
                    $('.pbooked_date').val(data['app_booked_date']);
                    $('.pbooked_time').val(data['app_booked_time']);
                    $('.pinTime').val(data['in_time']);
                    $('.poutTime').val(data['out_time']);
                    $('.ptherapist_id').val(data['therapist_id']);
                }
            });
        }
    });

    var $star_rating = $('.star-rating .fa');

    var SetRatingStar = function() {
      return $star_rating.each(function() {
        if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
          return $(this).removeClass('fa-star-o').addClass('fa-star');
        } else {
          return $(this).removeClass('fa-star').addClass('fa-star-o');
        }
      });
    };

    $star_rating.on('click', function() {
      $star_rating.siblings('input.rating-value').val($(this).data('rating'));
      return SetRatingStar();
    });
    SetRatingStar();

    $('#example1').on('click', '.packageRating', function(){
        var id = $(this).data('id');
        $('.packageTreatmentId').val(id);
        return true;

    });

    $('#example1').on('click','.therapistAssigned', function(){
        var id = $(this).data('id');
        $('.userId').val(id);
        return true;
    });

    $('#example1').on('click', '.markAttandance', function(){
        var id = $(this).data('id');
        $('.therapistId').val(id);
        return true;
    });

    $('#example1').on('click', '.appointmentComplete', function(){
        var id = $(this).data('id');
        $('.AppontmentId_complete').val(id);
        return true;
    });

    $('#example1').on('click', '.therapistPenalty', function(){
        var id = $(this).data('id');
        $('.therapistId').val(id);
        return true;
    });

    $('.appointmentPerDayVisit').click(function(e) {
    // $('.appointmentPerDayVisit').on('show.bs.modal', function (e) {
        // var url = $(this).data('url');
        // var appId = $(this).data('ids');
        // $.ajax({
        //     url: url+'/'+appId,
        //     type: 'GET',
        //     dataType: 'json',
        //     success:function(data){
        //         if(data == 'true'){
                    var id = $(this).data('id');
                    $('.Appontment_id_day').val(id);
                    return true;
        //         }else{
        //             swal({
        //               title: 'You can book only one or two appointment Today!!',
        //               text: "",
        //               type: 'warning',
        //               showCancelButton: true,
        //               confirmButtonColor: '#3085d6',
        //               cancelButtonColor: '#d33',
        //               confirmButtonText: 'Okay!'
        //             }).then((result) => {
        //               e.stopPropagation();
        //               // return false;
        //             });
        //             e.stopPropagation();
        //         }
        //     }
        // });
    });

    $('.appointmentPackageWise').click(function() {
        var id = $(this).data('id');
        $('.Appontment_id_package').val(id);
        return true;
    });

    $('.updateVisit').click(function(){
        var id = $(this).data('id');
        $('.visitId').val(id);
        return true;
    });

    $('.generateInvoice').click(function(){
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        $('.visitId').val(id);
        $('.amount').val(amount);
        return true;
    });

    $('.packageInvoice').click(function(){
        var id = $(this).data('id');
        var amount = $(this).data('amount');
        $('.appId').val(id);
        $('.amount').val(amount);
        return true;
    });

    $('.convertPackage').on('change', function() {
        var id = $(this).data('id');
        var convertPackage = $('.convertPackage option:selected').val();
        if(convertPackage == 'PackageType'){
            $('.convert1').css("display", "block");
        }else{
            $('.convert1').css("display", "none");
        }
    });

	$('.patientType').on('change', function() {
		var patientType = $('.patientType option:selected').val();
		if(patientType == 'old'){
			$('.box1').css("display", "block");
			$('.box2').css("display", "none");
		}else if(patientType == 'new'){
			$('.box1').css("display", "none");
			$('.box2').css("display", "block");
		}else{
			$('.box1').css("display", "none");
			$('.box2').css("display", "none");
		}
	});

    $('.service_type').on('change', function(){
        var serviceType = $('.service_type option:selected').val();
        if(serviceType == '7'){
            $('.box3').css("display", "block");
        }else if((serviceType == '9') || (serviceType == '1') || (serviceType == '8')){
            $('.homeCareTest').css("display", "block");
        }else{
            $('.box3').css("display", "none");
            $('.homeCareTest').css("display", "none");
        }
    });

    $(".datevalidate").change(function() {
        var d = new Date();
        var month = d.getMonth()+1;
        var day = d.getDate();
        var currentDate = d.getFullYear() + '-' + ((''+month).length<2 ? '0' : '') + month + '-' + ((''+day).length<2 ? '0' : '') + day;
        var selectedDate = $(this).val();
        if(selectedDate < currentDate){
            alert('Please select today date only, dont do smart work!!');
            $('.datevalidate').val('');
            return false;
        }else{
            return true;
        }
    });

    $('#imageFile').on('change', function(event) {
        var formData = new FormData();
        var file = document.getElementById("imageFile").files[0];
        formData.append("Filedata", file);
        var s = file.name.split('.');
        if(s.length <= 2){
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png") {
                alert('Please select a valid file');
                document.getElementById("imageFile").value = '';
                return false;
            }
            return true;
        }else{
            alert('These file look like harmful for our system!!');
            document.getElementById("imageFile").value = '';
            return false;
        }
    });

    $('#videoFile').on('change', function (event) {
        var formData = new FormData();
        var file = document.getElementById("videoFile").files[0];
        formData.append("Filedata", file);
        var s = file.name.split('.');
        if(s.length <= 2){
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "mp3" && t != "mp4") {
                alert('Please select a valid file');
                document.getElementById("videoFile").value = '';
                return false;
            }
            return true;
        }else{
            alert('These file look like harmful for our system!!');
            document.getElementById("videoFile").value = '';
            return false;
        }
    });

    $('#imageFile').change(function() {
      readURL(this);
    });

    function readURL(input){
      if(input.files && input.files[0]){
        var reader = new FileReader();
        reader.onload = function(e){
          $('#profile_picture').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
    
    $('#docfile1').on('change', function(event) {
        var formData = new FormData();
        var file = document.getElementById("docfile1").files[0];
        formData.append("Filedata", file);
        var s = file.name.split('.');
        if(s.length <= 2){
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png" && t != "pdf" && t != "doc") {
                alert('Please select a valid file');
                document.getElementById("docfile1").value = '';
                return false;
            }
            return true;
        }else{
            alert('These file look like harmful for our system!!');
            document.getElementById("docfile1").value = '';
            return false;
        }
    });

    $('#docfile2').on('change', function(event) {
        var formData = new FormData();
        var file = document.getElementById("docfile1").files[0];
        formData.append("Filedata", file);
        var s = file.name.split('.');
        if(s.length <= 2){
            var t = file.type.split('/').pop().toLowerCase();
            if (t != "jpeg" && t != "jpg" && t != "png" && t != "pdf" && t != "doc") {
                alert('Please select a valid file');
                document.getElementById("docfile1").value = '';
                return false;
            }
            return true;
        }else{
            alert('These file look like harmful for our system!!');
            document.getElementById("docfile1").value = '';
            return false;
        }
    });
});

function appTypeAction(){
	var appType = $('#appType option:selected').val();
	if(appType == 'online_form'){
        $('.test1').css("display", "block");
        $('.test2').css("display", "none");
        $('.test3').css("display", "none");
        $('.test4').css("display", "block");
    }else if(appType == 'upload_media'){
    	$('.test2').css("display", "block");
    	$('.test1').css("display", "none");
        $('.test3').css("display", "none");
        $('.test4').css("display", "block");
    }else if(appType == 'consultant'){
    	$('.test3').css("display", "block");
    	$('.test2').css("display", "none");
        $('.test1').css("display", "none");
        $('.test4').css("display", "block");
    }else if(appType == 'callback'){
    	$('.test2').css("display", "none");
        $('.test3').css("display", "none");
        $('.test1').css("display", "none");
        $('.test4').css("display", "block");
    }else{
    	$('.test1').css("display", "none");
        $('.test2').css("display", "none");
        $('.test3').css("display", "none");
        $('.test4').css("display", "block");
    }
}
