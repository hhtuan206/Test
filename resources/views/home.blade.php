@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table id="table" class="table table-hover">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Update</th>
                                <th>Create</th>
                            </tr>

                            </thead>
                            <tbody id="table-data">

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @can('create')
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Create</div>

                    <form method="post">
                        <div class="card-body">
                            <div class="form-group">
                                <input type="text" class="form-control input-solid" id="name" name="name"
                                       placeholder="name">
                            </div>

                        </div>
                        <div class="card-footer text-left">
                            <button class="btn btn-primary create">Create</button>
                        </div>
                    </form>

                </div>
            </div>
            @endcan
        </div>
    </div>
@stop
@section('scripts')
    <script>
        let token = 'Bearer ' + getCookie('token')

        function getCookie(name) {
            var nameEQ = name + "=";
            var ca = document.cookie.split(';');
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function converTime(time) {
            const date = new Date(time);
            return date.getFullYear() + '/' + date.getHours() + '/' + date.getMinutes();
        }

        function getData() {
            $("#table-data").empty()
            $.ajax({
                url: '/api/blogs',
                type: "get",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "Authorization": token,
                },
                success: function (res) {
                    $.each(res, function (i, v) {
                        $("#table-data").append(
                            $("<tr></tr>").append([
                                $("<td class='id'>" + v.id + "</td>"),
                                $("<td class='editableColumns'>" + v.name + "</td>"),
                                $("<td>" + converTime(v.updated_at) + "</td>"),
                                $("<td>" + converTime(v.created_at) + "</td>"),
                                @can('update')
                                $("<td><button class='btn btn-secondary editValues'>Update</button></td>"),
                                $("<td><button class='btn btn-danger deleteValues'>Delete</button></td>"),
                                @endcan
                            ])
                        )

                    })
                }
            })
        }

        $(document).ready(function () {

            getData();

            $(document).on('click', '.editValues', function () {
                $(this).hide();
                $(this).parents('tr').find('td.editableColumns').each(function () {
                    var html = $(this).html();
                    var input = $('<input class="form-control" name="name" type="text"/>');
                    input.val(html);
                    $(this).html(input);
                });
            });
            $(document).on('click', '.deleteValues', function () {
                let id = $(this).parents('tr').find('td.id').html()
                $.ajax({
                    url: 'api/blogs/' + id,
                    type: "delete",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "Authorization": token,
                    },
                    success: function (data, textStatus, jqXHR) {
                        alert(textStatus + ": " + jqXHR.status);
                        getData()
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        getData()
                    }
                })
            });

            $(document).on('focusout', '.editableColumns', function () {
                let id = $(this).parents('tr').find('td.id').html()
                $.ajax({
                    url: 'api/blogs/' + id,
                    type: "put",
                    data:
                        JSON.stringify($(this).parents('tr').find('td.editableColumns').html())
                    ,
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "Authorization": token,
                    },
                    success: function (data, textStatus, jqXHR) {
                        alert(textStatus + ": " + jqXHR.status);
                        getData()
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        getData()
                    }
                })
            })

            $('.create').click(function (e) {
                e.preventDefault()
                $.ajax({
                    url: 'api/blogs',
                    type: "post",
                    headers: {
                        "Authorization": token,
                    },
                    data: {
                        'name': ($('#name').val())
                    },
                    crossDomain: true,
                    success: function (data, textStatus, jqXHR) {
                        alert(textStatus + ": " + jqXHR.status);
                        getData()
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        alert(textStatus + ": " + jqXHR.status + " " + errorThrown);
                        getData()
                    }
                })
            })
        })

    </script>
@stop


