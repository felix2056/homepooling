@extends('layouts.adminNew')

@section('pageTitle')
Gallery
@endsection

@section('content')
<!-- Section header -->
    <section class="content-header">
        <h1>
            Gallery
            <small>Images</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('admin.home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li><a href="#"><i class="fa fa-camera"></i> Gallery</a></li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <div class="box-header">
                        <h3 class="box-title">All Property Images</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                @foreach($images as $image)
                                    <div class="col-xs-6 col-md-3 thumbnail">
                                        <img class="img-responsive" src="{{asset('/storage/img/thumbs/'.$image->url)}}" alt="image">
                                        <div class="middle">
                                            <a href="#0" data-id="{{$image->id}}" class="remove-image" title="Delete Image" ><i class="fa fa-5x fa-remove"></i> </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection