@extends('layouts.adminNew')

@section('pageTitle')
Timeline
@endsection

<!-- BEGIN PAGE CONTENT-->
@section('content')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({selector:'textarea'});</script>
    <!-- Section header -->
    <section class="content-header">
        <h1>
            FAQ
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('admin.home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">FAQ</li>
        </ol>
    </section>
    <!-- ./Section header -->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                        <div class="box-header">
                            <h3 class="box-title">FAQ <span class="text-danger"> * Marks are required feild</span></h3>
                        </div>

                        <!-- /.box-header -->
                        <div class="box-body">
                            <form  id="faqForm" action="{{URL::Route('admin.terms')}}" method="post">
                            {{ csrf_field() }}
                            <div class="form-group has-feedback">
                                <label for="terms">Terms & Conditions <span class="text-danger">*</span></label>
                                <textarea  name="terms" class="form-control textarea" required minlength="5" >@if($terms){{ $terms }}@endif</textarea>
                                <span class="glyphicon glyphicon-info form-control-feedback"></span>
                                <span class="text-danger">{{ $errors->first('terms') }}</span>
                            </div>
                            <div class="form-group">
                                <a href="{{URL::route('admin.home')}}" class="btn btn-default">Cancel</a>
                                <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>

                            </div>
                            <hr>
                    </form>
                </div>
                <!-- /.box-body -->

                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection
<!-- END PAGE CONTENT-->