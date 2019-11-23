<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>{{ config('app.name', 'Homepooling') }}</title>

	<!-- Styles -->
        <style>
            .listing-block {
                width: 100%;
                float: left;
                padding: 1.5em;
                font-family: 'Open Sans',sans-serif;
                opacity:1;
                transition:all .5s ease;
            }
            .listing-block a{
                text-decoration:none;
            }
            .listing-block .listing-info, .listing-block .listing-image{
                transition:all .5s ease;
            }
            .listing-block:hover .listing-info,.listing-block:hover .listing-image{
                    box-shadow: 0 8px 16px 0 rgba(0,0,0,.2);
            }
            .listing-block {
                width: 400px;
            }
            .listing-block .listing-visitors,.viewers .listing-visitors {
                position: relative;
                margin: -1.6em 0;
                padding-right: .3em;
                text-align: right;
                z-index: 99
            }
            .viewers .listing-visitors {
                margin: -7em 0;
                padding-right: 7.5em;
            }
            .listing-visitors a.visitor{
                    text-align:center;
                    font-size:25px;
                    font-weight:800;
                    color:#fff;
                    padding-right:2px;
                    position:relative;
            }
            
            .listing-visitors a.visitor.visitor-main{
                    text-align:center;
                    font-size:30px;
                    font-weight:800;
                    color:#fff;
                    padding-right:2px;
            }
            .listing-block .listing-visitors .visitor, .viewers .listing-visitors .visitor{
                display: inline-block;
                vertical-align: middle;
                width: 40px;
                height: 40px;
                border: 3px solid #fff;
                border-radius: 50%;
                background-color: #43e695
            }

            .listing-block .listing-visitors .visitor-main,.viewers .listing-visitors .visitor-main {
                width: 45px;
                height: 45px
            }
            .viewers .listing-visitors .visitor-main, .viewers .listing-visitors .visitor,.listing-block .listing-visitors .visitor,.listing-block .listing-visitors .visitor-main{
                    background-color:#76c36a;
                    background-size:cover;
                    background-repeat:no-repeat;
            }
            .listing-block .listing-info {
                font-family: 'Open Sans',sans-serif;
                position: relative;
                padding: 2em 1.2em;
                border-radius: 0 0 .8em .8em;
                background-color: #EBEBEB
            }

            .listing-block .listing-info a {
                    color: #2b2d2e;
                    font-size: 1.1em;
                    font-weight: 600;
                    display:block;
                    width:80%;
                    text-decoration:none;
            }

            .listing-block .listing-info span {
                display: block;
                margin-top: .2em;
                color: #696d70
            }

            .listing-image {
                display: block;
                position: relative;
                max-height:180px;
                background-color:#dddddd;
                    min-height:180px;
                    display:flex;
                    justify-content:center;
            }

            .listing-image img {
                width: 100%;
                object-fit:cover;
            }

            .listing-price,
            .listing-label {
                position: absolute;
                left: -6px;
                color: #fff;
                font-size: 1.2em;
                transform:translateX(-6px);
            }

            .listing-price:after,
            .listing-label:after {
                content: '';
                position: absolute;
                top: 100%;
                left: 0;
                border: 3px solid;
                border-color: #2b2d2e #2b2d2e transparent transparent
            }

            .listing-price {
                bottom: 1em;
                padding: .5em 1.5em;
                background-color: #3a3a3a
            }

            .listing-label {
                top: 1em;
                padding: .4em 1.5em;
                text-transform: uppercase
            }

            .listing-label.label-new,.listing-label.label-early {
                background-color: #009ee3;
                border-radius: 0 0 10px 0
            }
            .listing-label.label-early{
                    background-color:#f78a04;
            }
            h4,#logo{
                width:100%;
                text-align:center;
            }
        </style>
		<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet"> 
</head>
<body>
    <div id="logo"><img src="{{url('/storage/img/logo_blue.png')}}"></div>
    <h4>Properties matching your Search Preferences</h4>
    <table>
    @foreach($properties as $property)
        @if($loop->first||$loop->iteration%3==1)
		<tr>
        @endif
		<td>
        <div class="listing-block">
                <a href="{{url('/properties/'.$property->id)}}" class="listing-image">
                        @if(isset($property->images) && $preview=$property->images()->first())
                                @if(file_exists(public_path(str_replace(basename($preview->url),'thumbs/'.basename($preview->url,'.jpeg').'_th.jpg',$preview->url))))
                                        <img src="{{url(str_replace(basename($preview->url),'thumbs/'.basename($preview->url,'.jpeg').'_th.jpg',$preview->url))}}">
                                @else
                                        <img src="{{url($preview->url)}}">
                                @endif
                        @else
                                <img src="url(/storage/img/placeholder.png)">
                        @endif
                        <span class="listing-price">&#8364;{{ isset($property->price) ? $property->price : ((isset($property->rooms) && isset($property->rooms[0]->price)) ? $property->rooms[0]->price : '') }}</span>
                </a>
                <div class="listing-info">
                        <a href="{{url('/properties/'.$property->id)}}">{{ $property->address }}</a>
                        <div class="contactable"><i class="fa fa-comment-o" style="font-size:2em;color:#3a3a3a"></i> @if($now->diffInDays($property->created_at)>7 || $property->early_access==1 || (\Auth::check() && \Auth::user()->early_bird==1)) <i class="fa fa-check" style="color:#42e695"></i> @else <i class="fa fa-close" style="color:#993b38"></i> @endif</div>
                </div>
        </div>
        </td>
        @if($loop->iteration%3==0)
		</tr>
        @endif
    @endforeach
    </table>
</body>
</html>