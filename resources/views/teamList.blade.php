@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Team List') }}
                    <span class="float-right" href="javascript:void(0)" name="addNewTeam" id="addNewTeam">
                        <button class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Team</button>
                    </span>
                </div>
                <table class="table table-bordered data-table table-striped" id="teamTable">
                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>State</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<div id="formModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<button type="button" class="close" data-dismiss="modal">&times;</button>-->
                <h4 class="modal-title">Add New Team</h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4" >Team Name : </label>
                        <div class="col-md-12">
                            <input type="text" name="name" id="name" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">State : </label>
                        <div class="col-md-12">
                            <input type="text" name="state" id="state" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Team Logo : </label>
                        <div class="col-md-12">
                            <input type="file" name="logo" id="logo" />
                            <span id="store_image"></span>
                        </div>
                    </div>
                    <br />
                    <div class="form-group" align="center">
                        <input type="hidden" name="action" id="action" />
                        <input type="hidden" name="hidden_id" id="hidden_id" />
                        <input type="submit" name="action_button" id="action_button" class="btn btn-primary" value="Add" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="confirmModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Confirmation</h2>
            </div>
            <div class="modal-body">
                <h4 align="center" style="margin:0;">Are you sure to delete this team?</h4>
            </div>
            <div class="modal-footer">
                <button type="button" name="ok_button" id="ok_button" class="btn btn-danger">OK</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {

        $('#teamTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('teams.index') }}",
            },
            columns: [
                {
                    data: 'logoUri',
                    name: 'logoUri',
                    render: function (data, type, full, meta) {
                        return "<img src={{ URL::to('/') }}/images/" + data + " width='70' class='img-thumbnail' />";
                    },
                    orderable: false
                },
                {
                    data: 'teamName',
                    name: 'teamName'
                },
                {
                    data: 'state',
                    name: 'state'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false
                }
            ],
            'columnDefs': [
                {
                    "targets": 0, 
                    "className": "text-center",
                    "width": "10%"
                },
                {
                    "targets": 3, 
                    "className": "text-center",
                    "width": "30%"
                }]
        });

        $('#addNewTeam').click(function () {
            $('.modal-title').text("Add New Team");
            $('#action_button').val("Add");
            $('#action').val("Add");
            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add')
            {
                $.ajax({
                    url: "{{ route('teams.store') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data)
                    {
                        var html = '';
                        if (data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success)
                        {
                            html = '<div class="alert alert-success">' + data.success + '</div>';
                            $('#sample_form')[0].reset();
                            $('#teamTable').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);
                    }
                });
            }

            if ($('#action').val() == "Edit")
            {
                $.ajax({
                    url: "{{ route('teams.update') }}",
                    method: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: "json",
                    success: function (data)
                    {
                        var html = '';
                        if (data.errors)
                        {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++)
                            {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success)
                        {
                            $('#formModal').modal('hide');
                            alert(data.success );
                            $('#sample_form')[0].reset();
                            $('#store_image').html('');
                            $('#teamTable').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).attr('teamId');
            $('#form_result').html('');
            $.ajax({
                url: "/teams/" + id + "/edit",
                dataType: "json",
                success: function (html) {
                    $('#name').val(html.data.teamName);
                    $('#state').val(html.data.state);
                    $('#store_image').html("<img src={{ URL::to('/') }}/images/" + html.data.logoUri + " width='70' class='img-thumbnail' />");
                    $('#store_image').append("<input type='hidden' name='hidden_image' value='" + html.data.logoUri + "' />");
                    $('#hidden_id').val(html.data.teamId);
                    $('.modal-title').text("Edit Team");
                    $('#action_button').val("Edit");
                    $('#action').val("Edit");
                    $('#formModal').modal('show');
                }
            });
        });

        var teamId;

        $(document).on('click', '.delete', function () {
            teamId = $(this).attr('teamId');
            $('#confirmModal').modal('show');
        });
        
        $(document).on('click', '.tmDtls', function () {
            teamId = $(this).attr('teamId')
            window.location='{{ url("/players/details") }}'+'/'+teamId;
        });

        $('#ok_button').click(function () {
            $.ajax({
                url: "teams/destroy/" + teamId,
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data)
                {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#teamTable').DataTable().ajax.reload();
                        $('#ok_button').text('OK');
                    }, 2000);
                }
            });
        });
    });
</script>
