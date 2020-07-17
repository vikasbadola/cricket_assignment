@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Player List') }}
                    <span class="float-right" href="javascript:void(0)" name="addNewPlayer" id="addNewPlayer">
                        <button class="btn btn-success btn-sm"><i class="fa fa-plus"></i> Add Player</button>
                    </span>
                </div>
                <table class="table table-bordered data-table table-striped" id="playerTable">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Jersey#</th>
                            <th>Team</th>
                            <th>Country</th>
                            <th></th>
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
                <h4 class="modal-title">Add New Player</h4>
            </div>
            <div class="modal-body">
                <span id="form_result"></span>
                <form method="post" id="sample_form" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label class="control-label col-md-4" >First Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="firstName" id="firstName" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Last Name : </label>
                        <div class="col-md-8">
                            <input type="text" name="lastName" id="lastName" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Select Team : </label>
                        <div class="col-md-8">
                            <select name="teamName" id="teamName">
                                <option value="">Select</option>
                                @foreach ($teamList as $team)
                                    <option value="{{$team->teamId}}">{{$team->teamName}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Jersey# : </label>
                        <div class="col-md-8">
                            <input type="text" name="jerseyNo" id="jerseyNo" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Country : </label>
                        <div class="col-md-8">
                            <input type="text" name="country" id="country" class="form-control" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-4">Select Image : </label>
                        <div class="col-md-8">
                            <input type="file" name="imageUri" id="imageUri" />
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
                <h4 align="center" style="margin:0;">Are you sure to delete this player?</h4>
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
        @if(!empty($teamId))
            var url = "/players/details/" + {{$teamId}};
        @else
            var url = "{{ route('players.index') }}";
        @endif
        console.log(url);
        $('#playerTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: url,
            },
            columns: [
                {
                    data: 'imageUri',
                    name: 'imageUri',
                    render: function (data, type, full, meta) {
                        return "<img src={{ URL::to('/') }}/images/" + data + " width='70' class='img-thumbnail' />";
                    },
                    orderable: false
                },
                {
                    data: 'firstName',
                    name: 'firstName'
                },
                {
                    data: 'lastName',
                    name: 'lastName'
                },
                {
                    data: 'jerseyNo',
                    name: 'jerseyNumber'
                },
                {
                    data: 'team',
                    name: 'team',
                    render: function (data, type, full, meta) {
                        return data.teamName;
                    },
                },
                {
                    data: 'country',
                    name: 'country'
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
                }],
        });

        $('#addNewPlayer').click(function () {
            $('.modal-title').text("Add New Player");
            $('#action_button').val("Add");
            $('#action').val("Add");
            $('#formModal').modal('show');
        });

        $('#sample_form').on('submit', function (event) {
            event.preventDefault();
            if ($('#action').val() == 'Add')
            {
                $.ajax({
                    url: "{{ route('players.store') }}",
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
                            $('#playerTable').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);
                    }
                })
            }

            if ($('#action').val() == "Edit")
            {
                $.ajax({
                    url: "{{ route('players.update') }}",
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
                            $('#playerTable').DataTable().ajax.reload();
                        }
                        $('#form_result').html(html);
                    }
                });
            }
        });

        $(document).on('click', '.edit', function () {
            var id = $(this).attr('playerId');
            $('#form_result').html('');
            $.ajax({
                url: "/players/" + id + "/edit",
                dataType: "json",
                success: function (html) {
                    $('#firstName').val(html.data.firstName);
                    $('#lastName').val(html.data.lastName);
                    $('#jerseyNo').val(html.data.jerseyNo);
                    $('#country').val(html.data.country);
                    $("#teamName").val(html.data.teamId);
                    $('#store_image').html("<img src={{ URL::to('/') }}/images/" + html.data.imageUri + " width='70' class='img-thumbnail' />");
                    $('#store_image').append("<input type='hidden' name='hidden_image' value='" + html.data.imageUri + "' />");
                    $('#hidden_id').val(html.data.playerId);
                    $('.modal-title').text("Edit Player");
                    $('#action_button').val("Edit");
                    $('#action').val("Edit");
                    $('#formModal').modal('show');
                }
            })
        });

        var playerId;

        $(document).on('click', '.delete', function () {
            playerId = $(this).attr('playerId');
            $('#confirmModal').modal('show');
        });
        
        $(document).on('click', '.tmDtls', function () {
            playerId = $(this).attr('playerId')
            window.location='{{ url("players/details") }}'+'/'+playerId;
        });

        $('#ok_button').click(function () {
            $.ajax({
                url: "/players/destroy/" + playerId,
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data)
                {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#playerTable').DataTable().ajax.reload();
                        $('#ok_button').text('OK');
                    }, 2000);
                }
            })
        });
    });
</script>