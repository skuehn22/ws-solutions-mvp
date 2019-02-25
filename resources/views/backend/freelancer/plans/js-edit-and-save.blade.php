    <script>

        //initalize the arrow bar on the top of the modal
        $('#smartwizard').smartWizard({
            selected: 0,
            theme: 'default',
            transitionEffect:'slide',
            autoAdjustHeight: false,
            toolbarSettings: {toolbarPosition: 'none',
                toolbarExtraButtons: [
                    {label: 'Finish', css: 'btn-success', onClick: function(){ alert('Finish Clicked'); }},
                    {label: 'Cancel', css: 'btn-warning', onClick: function(){ $('#smartwizard').smartWizard("reset"); }}
                ]
            }
        });

        $('#smartwizard').smartWizard("theme", "default");

        // External Button Events
        $(".next-btn").on("click", function() {
            // Reset wizard
            $('#smartwizard').smartWizard("next");
            return true;
        });

        $(".prev-btn").on("click", function() {
            // Navigate previous
            $('#smartwizard').smartWizard("prev");
            return true;
        });


        $( document ).ready(function() {
            loadScript();
            $('.btn-group').removeClass("step-content");

            $("#typ").val( {{$plan->typ}} );

            @if(isset($milestones_edit))
                getPlanTyp({{$plan->id}});
            @endif

        });

        // External Button Events
        $("#create-client-fly").on("click", function() {
            $('#create-client-modal').modal('show');
        });

        // External Button Events
        $(".close-saved").on("click", function() {
            $('#saved-modal').modal('hide');
        });

        //STEP 4: Toggle Buttons for Payment Options
        @if($plan->credit_card == 1)
        $("#togBtn").click();
        @endif

        @if($plan->bank_transfer == 1)
        $("#togBtnBt").click();
        @endif

        $("#togBtn").on('change', function() {

            if ( $(this).val() == "true") {
                $(this).val('false');
            }
            else {
                $(this).val('true');
            }});

        $("#togBtnBt").on('change', function() {

            if ( $(this).val() == "true") {
                $(this).val('false');
            }
            else {
                $(this).val('true');
            }});

        //topmenu
        $(document).on("click", ".button-menu", function () {

            event.preventDefault();
            $.ajax({
                url:"{{ route('freelancer.plans.save') }}",
                method:"GET",
                data:$('#upload_form').serialize(),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    $('#msg').html(data.message);
                    $('#saved-modal').modal('show');
                }
            })
        });

        $("#preview-btn").on("click", function() {

            event.preventDefault();
            $.ajax({
                url:"{{ route('freelancer.plans.save') }}",
                method:"GET",
                data:$('#upload_form').serialize(),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    var plan = $("#plan").val();
                    mywindow = window.open("/payment-plan/load-preview/"+plan , "mywindow", "location=1,status=1,scrollbars=1,  width=1050,height=800");
                }
            })
        });


        $("#send-plan").on("click", function() {

            event.preventDefault();
            $.ajax({
                url:"{{ route('freelancer.plans.save') }}",
                method:"GET",
                data:$('#upload_form').serialize(),
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {

                    var plan = $("#plan").val();
                    event.preventDefault();
                    $.ajax({
                        url:"{{ route('freelancer.plan.send') }}",
                        method:"GET",
                        data: $('#upload_form').serialize(),
                        dataType:'JSON',
                        contentType: false,
                        cache: false,
                        processData: false,
                        success:function(data)
                        {
                            $('#msg').html(data.success);
                            $('#send-modal').modal('show');
                        }
                    })
                }
            })

        });


        //initalize datepicker
        $( function() {
            $( "#creation-date" ).datepicker();
        } );


        //loads projects for selected client
        $("#typ").on('change', function() {
            getPlanTyp($(this).val());
        });


        function getDocs(id) {

            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }

            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                    document.getElementById("uploaded_image").innerHTML = xmlhttp.responseText;
                    $('#uploaded_image').removeClass("d-none");
                    loadScript();
                }
            }


            xmlhttp.open("GET","{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/plans/docs?typ="+id, true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xmlhttp.send();
        }

        //load scripts after a ajax call
        function loadScript(){

            //adds tolltips
            $(function () {
                $('[data-toggle="tooltip"]').tooltip()
            });

            //loads projects for selected client
            $("#clients").on('change', function() {
                document.getElementById("projects").innerHTML = "";
                action('projects/by-client',  $(this).val());
                insertdata('clients/get-by-id-client',  $(this).val());
            });

            $("#projects-dropdown").on('change', function() {
                //action('projects/by-client',  $(this).val());
                insertdata('projects/get-by-id',  $(this).val());
            });

            $( function() {
                $( "#due-date" ).datepicker();
            } );

            //loads projects for selected client
            $("#pay-due").on('change', function() {

                if($(this).val() == 3){
                    loadScript();
                    $(".due").removeClass( "d-none" )
                    $(".amount").removeClass( "d-none" )
                }else{
                    $(".amount").removeClass( "d-none" )
                }
            });

            $(document).ready(function () {
                var counter = 0;

                $("#addrow").on("click", function () {
                    var newRow = $("<tr>");
                    var cols = "";

                    cols += '<td><input type="text" class="form-control" name="name' + counter + '"/></td>';
                    cols += '<td><input type="text" class="form-control" name="amount' + counter + '"/></td>';
                    cols += '<td><input type="text" class="form-control" id="due_date' + counter + '" name="due_date' + counter + '"/></td>';
                    cols += '<td><textarea class="form-control" rows="3" name="description' + counter + '"></textarea></td>';

                    cols += '<td><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></td>';
                    newRow.append(cols);
                    $("table.order-list").append(newRow);
                    $( "#due_date" + counter).datepicker();
                    counter++;
                });


                $("table.order-list").on("click", ".ibtnDel", function (event) {
                    $(this).closest("tr").remove();
                    counter -= 1
                });
            });



            function calculateRow(row) {
                var price = +row.find('input[name^="price"]').val();

            }

            function calculateGrandTotal() {
                var grandTotal = 0;
                $("table.order-list").find('input[name^="price"]').each(function () {
                    grandTotal += +$(this).val();
                });
                $("#grandtotal").text(grandTotal.toFixed(2));
            }

            //initalize datepicker
            $( function() {
                $( "#due_date" ).datepicker();
            } );


            //loads projects for selected client
            $(".delete-doc").on('click', function() {

                var doc = $(this).data('id');

                $.ajax({
                    type: 'GET',
                    url: '{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/plans/delete-doc',
                    data: { variable: doc },
                    dataType: 'json',

                    success: function(data) {

                        $("."+doc).hide();

                    }
                });
            });
        }


    function getPlanTyp(id) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {

            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                document.getElementById("plan-typ-response").innerHTML = xmlhttp.responseText;

                @if(isset($milestones_edit->due_typ))
                    $("#pay-due").val({{$milestones_edit->due_typ}});
                    $(".amount").removeClass("d-none");
                    getDocs({{$plan->id}});
                @endif

                loadScript();
            }
        };

        @if(isset($milestones_edit->due_typ))
            xmlhttp.open("GET","{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/plans/get-plan-typ/?typedit="+id, true);
        @else
            xmlhttp.open("GET","{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/plans/get-plan-typ/?typ="+id, true);
        @endif

        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }


    function savePlan() {

        var plan =  $("#plan").val();

        $.ajax({

            type: 'GET',
            url: '{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/plans/save/'+plan,
            data: { variable: 'value' },
            dataType: 'json',
            success: function(data) {

                var items = data["project"];

            }
        })
    }

    function action(url, id) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else {
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

                switch(url) {
                    case 'projects/by-client':
                        document.getElementById("projects").innerHTML = xmlhttp.responseText;
                        loadScript();
                        break;
                    case 'clients/get-by-id':
                        var client = JSON.parse(xmlhttp.responseText);
                        loadScript();
                        break;
                    default:
                    // code block
                }
            }
        };

        xmlhttp.open("GET","{{env("MYHTTP")}}/{{$blade["ll"]}}/freelancer/"+url+"/?id="+id, true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send();
    }

</script>