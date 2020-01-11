@extends('layouts.master')

@section('title', $course->title)

@section('content')
<div class="container-fluid">
    <div class="row app-row" style="padding-right: 380px">
        <div class="col-12">
            <div class="mb-2">
                <h1>{{ $course->title }}</h1>
                <div class="top-right-button-container">
                    <h3>
                        <span class="badge badge-pill badge-outline-primary mb-1">
                            <a href="{{ route('classes.show', ['id' => $course->module->classe_id]) }}">{{ $course->module->title }} / {{ $course->module->classe->title }}</a></span>
                        <a href="{{ route('courses.edit', ['id' => $course->id]) }}" class="btn btn-outline-success btn-sm">
                            <i class="simple-icon-pencil"></i>
                        </a>
                    </h3>
                </div>
            </div>
            <div class="separator mb-4"></div>
            <div class="card">
                <div class="card-body">
                    <div class="position-relative video-content">{!! $course->video_content !!}</div>
                    <div class="mt-4">
                        <div class="d-flex flex-row justify-content-between">
                            <div class="d-flex flex-row justify-content-between">
                                <a>
                                    <img src="{{ $course->teacher->user->picture_path }}" alt="Brynn Bragg" class="img-thumbnail border-0 rounded-circle list-thumbnail align-self-center xsmall">
                                </a>
                                <div class="pl-3">
                                    <a>
                                        <p class="font-weight-medium mb-1">{{ $course->teacher->user->full_name }}</p>
                                        <p class="text-muted mb-0 text-small">Publier le : </b>{{ Helper::formatDate($course->published_at, 'd.m.Y à h:i') }}</p>
                                    </a>
                                    <p class="mt-3">{{ $course->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="position-absolute card-top-buttons">
                    <button class="btn btn-outline-primary icon-button" data-toggle="modal" data-target="#modal-add-document">
                        <i class="simple-icon-plus"></i>
                    </button>
                </div>
                <div class="card-body">
                    <h5 class="card-title">Documents Attachées</h5>
                    <div class="scroll ps ps--active-y" style="max-height: 200px">
                        @forelse ($course->files as $file)
                            <div class="d-flex flex-row pb-3 justify-content-between {!! $loop->last ?: 'border-bottom mb-3' !!}">
                                <div class="flex-grow-1">
                                    <a href="{{ asset('storage/' . $file->path) }}" data-document-url="{{ asset('storage/' . $file->path) }}" data-document-title="{{ $file->title }}" class="btn-show-document">
                                        <p class="font-weight-medium mb-0">
                                            <i class="simple-icon-doc text-primary"></i> 
                                            &nbsp; {{ $file->title }}
                                        </p>
                                    </a>
                                </div>
                                <div class="comment-likes">
                                    <form method="post" action="{{ route('files.delete', ['id' => $file->id]) }}">
                                        @csrf
                                        @method('DELETE')
                                        <span data-confirmation-message="Voulez vous vraiment supprimer ce document ?" class="post-icon pointer-cursor btn-delete-file btn-delete-resource redirect-after-confirmation">
                                            <i class="simple-icon-trash text-danger ml-2"></i>
                                        </span>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-center">Aucune documents...</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="app-menu" style="width: 380px">
    <course-chat :course-id="{{ $course->id }}" :user-id="{{ auth()->user()->id }}"/>
</div>

{{-- Start Modals --}}
<div class="modal" id="modal-show-document" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header p-1rem">
                <h5 class="modal-title" id="modal-title"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-1rem">
                
            </div>
            <div class="modal-footer p-1rem">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<div class="modal" id="modal-add-document" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('courses.save_document', ['id' => $course->id]) }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title">Ajouter un document</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" required class="form-control" name="title" placeholder="Titre">
                    </div>
                    <div class="form-group">
                        <label for="title">Document</label>
                        <input type="file" required class="form-control" name="course_file">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fermer</button>
                    <button class="btn btn-sm btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- End Modals --}}
@endsection

@section('custom-stylesheet')
    <style>
        .video-content iframe {
            height: 400px !important;
            width: 100%;
            border-radius: 10px;
        }

        .chat-content {
            height: 400px !important; 
            background-color: #f8f8f8; 
            box-shadow: inset 0 1px 15px rgba(0,0,0,.04), 0 1px 6px rgba(0,0,0,.04); 
            padding: 10px; 
            border-radius: 10px
        }

        .chat-content .chat-message {
            padding: 15px;
        }

        .chat-content .my-message {
            background: #2a93d5 !important;
            color: #fff !important;
        }

        .chat-content .my-message p, 
        .chat-content .my-message .text-muted,
        .chat-content .my-message .text-semi-muted {
            color: #fff !important;
        }
    </style>
@endsection

@section('custom-javascript')
    <script type="text/javascript">
        $(document).ready(function () {
            // Events
            $('.btn-show-document').click(function (event) {
                event.preventDefault();
                $('#modal-show-document #modal-title').text($(this).data('document-title'));
                $('#modal-show-document #pdf-viewer').attr('data', $(this).data('document-url'));
                $('#modal-show-document .modal-body').html('<object type="application/pdf" id="pdf-viewer" data="' + $(this).data('document-url') + '" width="100%" style="height: 60vh;">No Support</object>');
                $('#modal-show-document').modal();
            });

            // $('.btn-delete-file').click(function () {
            //     $(this).parent().submit();
            // });
        });
    </script>
@endsection