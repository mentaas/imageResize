@extends('layouts.app')

@section('content')


    <div class="container mt-5 mb-5">
        <div class="row">
            <div class="col-md-12">
                <div class="loader" style="margin-left:auto;margin-right:auto;"></div>
                <div class="m-t-20" id="tableContent">
                    <table id="tbl" class="table-responsive table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th scope="col">Image</th>
                            <th scope="col">Max width</th>
                            <th scope="col">Max height</th>
                            <th scope="col">Link</th>
                            <th scope="col">Last update at</th>
                            <th scope="col">Action</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    </div>
    </div>
    <script type="text/javascript">

        $(document).ready(function(){

        });
        var loading = $('.loader').show();

        $(document)
            .ajaxStart(function () {
                loading.show();
            })
            .ajaxStop(function () {
                loading.hide();
            });
        $(document).ready(function (){
            $.ajax({
                url:'/api/resizeImages',
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {
                    console.log(data);
                    $.each(data, function (key, item) {
                        let showButton = "<a href="+item.random_generated_path+" target='_blank' class=\"btn btn-info btn-brand btn-brand-big\">Show</a>";
                        let deleteButton = "<button onclick='removeImage("+item.id+")' class=\"btn btn-danger btn-brand btn-brand-big\">Remove</button>";
                        $('#tbl > tbody').append("<tr><td>"+item.id+"</td><td><img src='data:"+ item.image_type +";base64,"+item.image_content+"' width='80' </td><td>"+item.max_width+"</td><td>"+item.max_height+"</td><td>"+item.random_generated_path+"</td><td>"+item.updated_at+"</td><td class='fixed'>"+showButton+"&nbsp;"+ deleteButton+"</td></tr>")
                    });
                    var table = $('#tbl').DataTable({
                        //"searching": false,
                        "lengthChange": false,
                        "pageLength": 10,
                        "order": [[ 5, "desc" ]],
                        dom: 'Bfrtip',
                        buttons: [
                            /*'copy', 'csv', 'excel', 'pdf', 'print'*/
                        ]

                    });
                },
                error: function (data) {
                    console.log(data);
                }
            });
        });

        function removeImage(id) {
            $.ajax({
                url: '/api/resizeImages/'+id,
                type: 'DELETE',
                dataType: 'JSON',
                success: function (data) {
                    location.reload();
                },
                error: function (data) {
                    console.log(data);
                }
            });
        }

    </script>
@endsection