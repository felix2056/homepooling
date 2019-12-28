@extends('layouts.adminNew')

@section('pageTitle')
Settings
@endsection

@section('content')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>tinymce.init({selector:'textarea'});</script>

 <!-- Section header -->
    <section class="content-header">
        <h1>
            Settings
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{URL::route('admin.home')}}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
            <li class="active">Settings</li>
        </ol>
    </section>
    <!-- ./Section header -->
<!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info">
                    <form action="{{ route('admin.settings') }}" method="post" novalidate="novalidate">
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="name">Application Name<span class="text-danger">*</span></label>
                                        <input autofocus type="text" name="name" class="form-control" placeholder="My Application" value="@if($info){{ $info['name'] }}@endif" maxlength="255" required />
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="short_name">Application Short Name<span class="text-danger">*</span></label>
                                        <input type="text" name="short_name" class="form-control" placeholder="MA" value="@if($info){{ $info['short_name'] }}@endif" minlength="3" maxlength="255" required />
                                        <span class="fa fa-info form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('short_name') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="logo">Logo<span class="text-danger"> [230 X 50 max size and max 1MB]</span></label>
                                        <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="logo" placeholder="logo image">
                                        @if($info && isset($info->logo))
                                            <input type="hidden" name="oldLogo" value="{{$info['logo']}}">
                                        @endif
                                        <span class="fa fa-image form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('logo') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group has-feedback">
                                        <label for="logo">Favicon<span class="text-danger"> [only .png image][32 X 32 exact size and max 512KB]</span></label>
                                        <input  type="file" class="form-control" accept=".jpeg, .jpg, .png" name="logo" placeholder="favicon image">
                                        @if($info && isset($info->logo))
                                            <input type="hidden" name="oldLogo" value="{{$info['logo']}}">
                                        @endif
                                        <span class="fa fa-image form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('logo') }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="website_link">Website Link</label>
                                        <input  type="text" class="form-control" name="website_link"  placeholder="url" value="@if($info) {{ $info['website_link'] }} @endif" maxlength="500">
                                        <span class="fa fa-link form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('website_link') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="email">Email</label>
                                        <input  type="email" class="form-control" name="email"  placeholder="email address" value="@if($info) {{ $info['email'] }} @endif" maxlength="255">
                                        <span class="fa fa-envelope form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="phone_no">Phone/Mobile No.<span class="text-danger">*</span></label>
                                        <input  type="text" class="form-control" name="phone_no" required placeholder="phone or mobile number" value="@if($info) {{ $info['phone_no'] }}@endif" minlength="8" maxlength="255">
                                        <span class="fa fa-phone form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group has-feedback">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" name="description" placeholder="Description" maxlength="500" rows="5">
                                            @if($info) {{ $info['description'] }} @endif
                                        </textarea>
                                        <span class="fa fa-link form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('description') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="facebook">Facebook Link</label>
                                        <input  type="url" class="form-control" name="facebook"  placeholder="facebook url" value="@if($info) {{ $info['facebook'] }} @endif" minlength="8" maxlength="500">
                                        <span class="fa fa-facebook form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('facebook') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="twitter">Twitter Link</label>
                                        <input  type="url" class="form-control" name="twitter"  placeholder="twitter url" value="@if($info) {{ $info['twitter'] }} @endif" minlength="8" maxlength="500">
                                        <span class="fa fa-twitter form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('twitter') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="youtube">Youtube Link</label>
                                        <input  type="youtube channel url" class="form-control" name="youtube"  placeholder="youtube url" value="@if($info) {{ $info['youtube'] }} @endif" minlength="8" maxlength="500">
                                        <span class="fa fa-youtube form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('youtube') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="youtube">Instagram Link</label>
                                        <input  type="youtube channel url" class="form-control" name="youtube"  placeholder="instagram url" value="@if($info) {{ $info['instagram'] }} @endif" minlength="8" maxlength="500">
                                        <span class="fa fa-instagram form-control-feedback"></span>
                                        <span class="text-danger">{{ $errors->first('instagram') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                        <div class="box-footer">
                            <a href="{{ route('admin.home') }}" class="btn btn-default">Cancel</a>
                            <button type="submit" class="btn btn-info pull-right"><i class="fa fa-check-circle"></i> Save</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection 