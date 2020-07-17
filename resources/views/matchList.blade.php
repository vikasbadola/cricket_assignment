@extends('layouts.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Match List') }}</div>
                <table class="table table-bordered data-table table-striped" id="teamTable">
                    <thead>
                        <tr>
                            <th>Team A</th>
                            <th>Team B</th>
                            <th>Winner</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function () {

        $('#teamTable').DataTable({
            ajax: {
                url: "{{ route('matches.index') }}",
            },
            columns: [
                {
                    data: 'team_a',
                    name: 'team_a',
                    render: function (data, type, full, meta) {
                        return data.teamName;
                    },
                },
                {
                    data: 'team_b',
                    name: 'team_b',
                    render: function (data, type, full, meta) {
                        return data.teamName;
                    },
                },
                {
                    data: 'winner',
                    name: 'winner',
                    render: function (data, type, full, meta) {
                        return data.teamName;
                    },
                },
                {
                    data: 'points',
                    name: 'points'
                }
            ],
        });
    });
</script>