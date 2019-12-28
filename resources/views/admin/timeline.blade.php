@extends('layouts.adminNew')

@section('pageTitle')
Timeline
@endsection
<!-- BEGIN PAGE CONTENT-->
@section('content')
    <!-- Section header -->
    <section class="content-header">
        <h1>
            Timeline
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('admin.home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Timeline</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">Timeline <span class="text-danger"> * Marks are required feild</span></h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <form  id="timeLineForm" action="{{URL::Route('admin.timeline')}}" method="post" enctype="multipart/form-data">

                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <label for="title">Title<span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="type" value="{{old('title')}}" maxlength="255" required />
                                <span class="glyphicon glyphicon-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('title') }}</span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="description">Description<span class="text-danger">*</span></label>
                                <textarea  name="description" class="form-control" required minlength="5" maxlength="500" >{{ old('description') }}</textarea>
                                <span class="glyphicon glyphicon-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('description') }}</span>
                            </div>
                            <div class="form-group has-feedback">
                                <label for="logo">Icon<span class="text-danger"> [56 X 56 max size and max 1MB]</span></label>
                                <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="icon" placeholder="timeline icon">
                                       
                                <span class="fa fa-image form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('icon') }}</span>
                            </div>
                            <div class="form-group">
                                <a href="{{URL::route('admin.home')}}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>

                            </div>
                            <hr>
                    </form>
                            <table id="data-table" class="table table-bordered table-striped list_view_table">
                                <thead>
                                <tr>
                                    <th width="30%">Title</th>
                                    <th width="50%">Description</th>
                                    <th width="10%">Icon</th>
                                    <th width="10%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($timeline as $line)
                                    @php
                                     $l = json_decode($line->meta_value);
                                    @endphp
                                    <tr>

                                        <td>{{ $l->t }}</td>
                                        <td>{{ $l->d }}</td>
                                        <td>
                                            <img class="img-responsive" style="max-height: 200px;" src="{{ asset('storage/img/home')}}/{{ $l->i }}" alt="timeline icon">
                                        </td>

                                        <td>
                                            <div class="btn-group">
                                                <form class="myAction" method="POST" action="{{URL::route('admin.timeline_delete',$line->id)}}">
                                                   {{ csrf_field() }}
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                                        <i class="fa fa-fw fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>

                                        </td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>

                        </div>
                        <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->