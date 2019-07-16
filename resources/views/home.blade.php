@extends('layouts.app')

@section('content')

<div class="container d-flex flex-wrap main justify-content-center">
    <div class="row rc align-self-center text-white">
        <div class="col">
            <div class="card text-center">
                <h1 class="card-header"> Welcome {{ Auth::user()->name }} </h1>

                <div class="card-body">

                    <div class="logout mb-4">
                        <a class="btn btn-dark btn-block" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();"><i class="fa fa-sign-out"></i> {{ __('Logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>

                    @if ( Auth::user()->type == "admin" )
                    <div class="table">
                        <table class="table" id="membersTable">
                        </table>
                    </div>
                    @endif
                </div>
            </div>

            @if ( Auth::user()->type == "admin" )
            <div class="modal fade" id="addForm-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title">Add new member</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <form id="addForm">
                                <div class="form-group">
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Enter email" required>
                                </div>
                                
                                <div class="form-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" id="addMember"> <i class="fas fa-user-plus"></i> Add Member</button>

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="editForm-modal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit this member</h5>
                            <button class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body">
                            <form id="editForm">

                                <input type="hidden" id="uid">

                                <div class="form-group">
                                    <input type="text" class="form-control" name="nameEdit" id="nameEdit" placeholder="Enter name" required>
                                </div>

                                <div class="form-group">
                                    <input type="email" class="form-control" name="emailEdit" id="emailEdit" aria-describedby="emailHelp" placeholder="Enter email" required>
                                </div>
                            </form>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-warning" id="editMember"> <i class="fas fa-user-times"></i> Edit Member</button>

                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    
</div>

@endsection

@section('script')
@if ( Auth::user()->type == "admin" )
<script>

    function getUsers( page ) {
        $.ajax({
            type : "GET",
            url : "/users?page=" + page,
            data : { action : "GET" },
            success : function( users ){
                var sno = users.total - ( 5 * ( page - 1 ) );
                $("#membersTable").html("");

                $('#membersTable').append(
                    "<tr>" +
                        "<td>Sno</td>"+
                        "<td>Name</td>"+
                        "<td>Email</td>"+
                        "<td>Action</td>" +
                    "</tr>"+
                    "<tr>" +
                        "<td colspan='4'><button class='btn btn-block btn-info' data-toggle='modal' data-target='#addForm-modal'><i class='fa fa-user-plus'></i> Add a Member</button></td>" +
                    "</tr>"
                );

                if ( users.data.length >= 1 ) {
                    users.data.forEach( all );

                    function all( user ){
                        $('#membersTable').append(
                            "<tr>" +
                                "<td>" + sno + "</td>"+
                                "<td>" + user.name + "</td>"+
                                "<td>" + user.email + "</td>"+
                                "<td>" +
                                    "<button type='button' onclick='editForm(" + user.id + ")' class='btn btn-xs btn-outline-warning px-1 py-0 m-1' data-toggle='modal' data-target='#editForm-modal' ><i class='fa fa-user-edit'></i></button>" +
                                    "<button type='button' onclick='delMember(" + user.id + ")' class='btn btn-xs btn-outline-danger px-1 py-0 m-1'  ><i class='fa fa-user-times'></i></button>" +
                                "</td>" +
                            "</tr>"
                        );
                        sno--;
                    }

                    var buttons = "";

                    for (let i = 1; i <= users.last_page; i++) {
                        buttons += "<li class='page-item' id='page" + i + "'><a class='page-link' onclick='getUsers(" + i + ")'>" + i + "</a></li>";
                    }

                    $('#membersTable').append(
                        "<tr>" +
                            "<td colspan='4'>" +
                                    "<nav aria-label='Page navigation example'>" +
                                        "<ul class='pagination justify-content-center row'>" +
                                            "<li class='page-item disabled'><a class='page-link' style='color:white'>Pages</a></li>"+
                                            buttons +
                                        "</ul>" +
                                    "</nav>" +
                            "</td>" +
                        "</tr>"
                    );

                    $('#page' + page ).css( 'background-color' , 'rgba(255, 255, 255, 0.9)' );
                    $('#page' + page ).css( 'color' , 'black' );
                }
                else {
                    $('#membersTable').append(
                        "<tr>" +
                            "<td colspan='4'>No Members registered!</td>" +
                        "</tr>"
                    );
                }
            }
        });
    }

    $(document).ready( function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });        

        getUsers( 1 );

        $('#addMember').click( function() {
            $.ajax({
                type : "POST",
                url : "{{ route('users.index') }}",
                data : {
                    'name' : $('#name').val(),
                    'email' : $('#email').val(),
                    'password' : $('#password').val(),
                },
                success : function( response ) {
                    // console.log( response );

                    if ( ( response.errors ) ) {
                        var errorString = '';
                        
                        if( response.errors.name )
                            errorString += response.errors.name;

                        if( response.errors.email )
                            errorString += '\n' + response.errors.email;

                        if( response.errors.password )
                            errorString += '\n' + response.errors.password;


                        swal({
                            title: "Field Error(s)!",
                            text: errorString,
                            icon: "error",
                            button: "Let me Fix!",
                        });
                    }
                    else {
                        $("#membersTable").html("");
                        $('#addForm')[0].reset();
                        getUsers( 1 );
                        $('#addForm-modal').modal('hide');
                        swal({
                            title: "Done",
                            text: "Member added Successfully",
                            icon: "success",
                            button: "Great!",
                        });
                    }
                }
            });
        } );
        $('#editMember').click( function() {
            $.ajax({
                type: "PUT",
                url: "/users/" + $('#uid').val(),
                data: {
                    'name' : $('#nameEdit').val(),
                    'email' : $('#emailEdit').val(),
                },
                success: function( response ) {
                    $('#editForm-modal').modal('hide');
                    
                    getUsers( 1 );
                    
                    $('#addForm-modal').modal('hide');
                    swal({
                        title: "Done",
                        text: "Member Updated Successfully",
                        icon: "success",
                        button: "Great!",
                    });
                }
            });
        } );
    } );

    function delMember( id ) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this member!",
            icon: "warning",
            buttons: true,
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                $.ajax( {
                    type : "DELETE",
                    url : "/users/" + id,
                    data : {
                        'id' : id,
                    },
                    success : function( response ){
                        $("#membersTable").html("");
                        getUsers( 1 );
                        swal({
                            title: "Deleted!",
                            text: "Member Delted successfully",
                            icon: "success",
                        });
                    }
                } );
            }
        });
    }

    function editForm( id ){
        $.ajax({
            type: "GET",
            url: "/users/"+id,
            success : function( response ) {
                $('#nameEdit').val( response.name );
                $('#emailEdit').val( response.email );
                $('#uid').val( response.id );
            }
        });
    }
</script>
@endif
@endsection