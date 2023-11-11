@extends('layouts.app')
@section('main')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('errors'))
        <div class="alert alert-danger">
            @foreach (session('errors') as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
    
    <div class="container pt-4">
        <div class="row justify-content-between">
            <div class="col">
                <p>Welcome {{ Auth::user()->name }}</p>
            </div>
            <div class="col text-right">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTaskModal" >Add Task</button>
            </div>
        </div>

        <table class="table table-success table-striped mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Task</th>
                    <th scope="col">Status</th>
                    <th scope="col">Created At</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($tasks->isNotEmpty())
                    @foreach($tasks as $task)
                        <tr>
                            <th scope="row">{{ $task->id }}</th>
                            <td>{{ $task->task }}</td>
                            <td>{{ $task->status }}</td>
                            <td>{{ $task->created_at }}</td>
                            <td>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changeStatusModal" data-id="{{$task->id}}">Change Status</button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" data-taskid="{{$task->id}}">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="5">{{ 'No Data' }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div class="d-flex justify-content-center">
            {{ $tasks->links('pagination::bootstrap-5') }}
        </div>
    </div>
   
    <!-- Add Task Modal -->
    <div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskModalLabel" aria-hidden="true">    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Enter Task</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                        <form id="addtaskform" method="post" action="">
                              @csrf
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <input  hidden type="text" class="form-control" id="user_id" value="{{Auth::user()->id}}" name="user_id">
                                        <input  type="text" class="form-control" id="task" name="task"  placeholder="Enter Task" required>
                                    </div>
                                    <div class="form-group col-md-12 text-center pt-2">
                                        <button class="btn btn-primary submit_btn">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Status Modal -->
    <div class="modal fade" id="changeStatusModal" tabindex="-1" role="dialog" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeStatusModalLabel">Change Status</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Add your form or input fields for changing the status here -->
                    <form method="post" id="changestatusform">
                        <div class="form-group">
                            <input name='task_id' id="task_id"  class="form-control"  type="text" hidden>
                            <label for="newStatus">New Status:</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Select Status</option>
                                <option value="done">Done</option>
                                <option value="pending">Pending</option>
                            </select>
                        </div>
                        <!-- Add more fields as needed -->

                        <button  class="btn btn-primary changestatusbtn mt-3">Change Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

   <!-- Confirm Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Delete</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this task?
                </div>
                <div class="modal-footer">
                    <form action="{{ route('delete') }}" method="post">
                        @csrf
                        <input name='id' id="task_id_delete" class="form-control" type="text" hidden>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#changeStatusModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); 
                var taskId = button.data('id'); 
                $('#task_id').val(taskId); 
            });

            $('#confirmDeleteModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); 
                var taskId = button.data('taskid'); 
                $('#task_id_delete').val(taskId); 
            });
            $('#addtaskform').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                
                $.ajax({
                    url: "api/todo/add",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-API-KEY': 'helloatg'
                    },
                    success: function(response) {
                        if (response.status === 1) {
                            alert('Success: ' + response.message);
                            window.location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = "An error occurred.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        alert('Error: ' + errorMessage);
                    }
                });
            });

    
            $('#changestatusform').on('submit', function(event) {
                event.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: "api/todo/status",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-API-KEY': 'helloatg'
                    },
                    success: function(response) {
                        if (response.status === 1) {
                            alert('Success: ' + response.message);
                            window.location.reload();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = "An error occurred.";
                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            errorMessage = Object.values(xhr.responseJSON.errors)[0][0];
                        }
                        alert('Error: ' + errorMessage);
                    }
                });
            });
        });
    </script>
@endsection
