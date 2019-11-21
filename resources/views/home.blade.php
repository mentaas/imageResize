@extends('layouts.app')

@section('content')


    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="center-all">
                <h3 class="card-text" style="text-align: center; color: #919191">Best place for resizing
                    images</h3>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <form method="post" onsubmit="return false;">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <label for="image_url" class="col-sm-12 control-label text-star">Image
                                    URL</label>
                                <div class="input-group">
                                    <input name="image_url" id="image_url" type="text" class="form-control">
                                </div>
                                <span id="validate-url" class="text-danger"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">

                    <div class="row">
                    <div class="col-md-12">

                            <div class="form-group">
                                <div class="col-md-12">
                                    <div class="row">

                                        <div class="col-md-6">
                                            <label for="max_width" class="col-sm-12 control-label text-star">Max
                                                width</label>
                                            <div class="input-group">
                                                <input name="max_width" id="max_width" type="number" step="0.01" class="form-control">
                                            </div>
                                            <span id="validate-width" class="text-danger"></span>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="max_height" class="col-sm-12 control-label text-star">Max
                                                height</label>
                                            <div class="input-group">
                                                <input name="max_height" id="max_height" type="number" step="0.01"
                                                       class="form-control">
                                            </div>
                                            <span id="validate-height" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 center-all">
                        <button type="submit" onclick="saveResizeImage()">
                            <div class="button">
                                <a href="#" class="btn_v1">Submit</a>
                            </div>
                        </button>
                    </div>

            </div>


        </div>
            <br>
            <div class="loader" style="margin-left:auto;margin-right:auto;display: none"></div>
            <div id="box-alert" class="col-md-12 text-center m-t-10 d-none">
                <div id="box-alert-inner" class="alert alert-danger"></div>
            </div>

        <br/>

        <div id="frame-box" class="col-md-12 center-all m-t-10 d-none relative">
            <div><button class="btn-show">Show full screen</button></div>
            <iframe id="iframe-image" class="box-shadow"
                    style="background-color: gray; overflow-y: scroll; width: 100%; min-height: 300px; height: auto">

            </iframe>
        </div>
        </form>
    </div>
    </div>
    </div>
    <script type="text/javascript">
        var loading = $('.loader').hide();
        $(document)
            .ajaxStart(function () {
                loading.show();
            })
            .ajaxStop(function () {
                loading.hide();
            });
        function saveResizeImage(){
            hideFrame();
            if(!validateFrom()){
                return;
            }
            $.ajax({
                url:'/api/resizeImages',
                type: 'POST',
                dataType: 'JSON',
                data: {image_url: $('#image_url').val(), max_width: $('#max_width').val(), max_height: $('#max_height').val()},
                success: function (data) {
                    $("#box-alert").removeClass("d-none");
                    $("#box-alert-inner").removeClass("alert-danger").addClass("alert-success");
                    $("#box-alert-inner").text("Success");
                    $("#frame-box").removeClass("d-none");
                    $(".btn-show").on("click", function(){showFullImage(data.random_generated_path)});
                    $('#iframe-image').attr("src", data.random_generated_path);
                },
                error: function (data) {
                    $("#box-alert").removeClass('d-none');
                    $("#box-alert-inner").removeClass('alert-success').addClass('alert-danger');
                    $("#box-alert-inner").text(data.responseText);
                    $("#frame-box").addClass('d-none');
                }
            });
        }

        function validateFrom(){
            let check = true;
            if($('#image_url').val() == ""){
                $('#validate-url').text('Please fill out Image URL');
                check = false;
            }else{
                $('#validate-url').text('');
            }
            if ($('#max_width').val() == ""){
                $('#validate-width').text('Please fill out Max width');
                check = false;
            }else{
                $('#validate-width').text('');
            }
            if ($('#max_height').val() == ""){
                $('#validate-height').text('Please fill out Max height');
                check = false;
            }else{
                $('#validate-height').text('');
            }
            return check;
        }

        function showFullImage(url){
            window.open(url)
        }

        function hideFrame(){
            $("#box-alert").addClass("d-none");
            $("#frame-box").addClass("d-none");
        }

    </script>
@endsection