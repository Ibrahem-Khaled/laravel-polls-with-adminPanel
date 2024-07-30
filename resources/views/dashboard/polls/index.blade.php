@extends('layouts.dashboard')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>Polls</h3>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPollModal">
                Add Poll
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>company Name</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($polls as $poll)
                        <tr>
                            <td>{{ $poll->user->name }}</td>
                            <td>{{ $poll->title }}</td>
                            <td>{{ $poll->description }}</td>
                            <td>{{ $poll->price }}</td>
                            <td>{{ $poll->status }}</td>
                            <td>{{ $poll->visibility }}</td>
                            <td><img src="{{ Storage::url($poll->image) }}" alt="Poll Image" style="width: 50px;"></td>
                            <td>
                                <a href="{{ route('polls.questions.index', $poll->id) }}" class="btn btn-info">View
                                    Questions</a>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#editPollModal{{ $poll->id }}">
                                    Edit
                                </button>
                                <form action="{{ route('polls.destroy', $poll->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                        <!-- Edit Poll Modal -->
                        <div class="modal fade" id="editPollModal{{ $poll->id }}" tabindex="-1"
                            aria-labelledby="editPollModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editPollModalLabel">Edit Poll</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="{{ route('polls.update', $poll->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            @include('dashboard.polls.form', ['poll' => $poll])
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center">
                {{ $polls->links() }}
            </div>
        </div>
    </div>

    <!-- Add Poll Modal -->
    <div class="modal fade" id="addPollModal" tabindex="-1" aria-labelledby="addPollModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPollModalLabel">Add Poll</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('polls.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('dashboard.polls.form', ['poll' => new App\Models\Poll()])
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
