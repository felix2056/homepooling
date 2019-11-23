<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="robots" content="noindex, nofollow">
</head>
<body>
	<div style="width:100%;padding:20px;">
		<div style="padding:5px;background-color:#009ee2">
			<img style="height:50px;" src="{{public_path().'/storage/img/logo.png'}}">
		</div>
		<p>Homepooling<br>
		Address<br>
		VAT n°</p>
		<table style="width:100%;border:1px solid #cccccc;margin-top:50px;">
			<thead>
				<tr style="height:30px;border-bottom:1px solid #cccccc;background-color:#cccccc">
					<td style="padding:5px;"><strong>Client name</strong></td>
					<td style="padding:5px;"><strong>Product name</strong></td>
					<td style="padding:5px;"><strong>Price paid</strong></td>
				</tr>
			</thead>
			<tbody>
				<tr style="height:30px;">
					<td style="padding:5px;">{{$name}}</td>
					<td style="padding:5px;">{{$product_name}}</td>
					<td style="padding:5px;"><strong>{{$amount}}<strong></td>
				</tr>
			</tbody>
		</table>
		<div style="position:absolute;bottom:20px;width:100%;background-color:#009ee2;padding:10px;color:#ffffff">© 2017 Homepooling.com</div>
	</div>
</body>
</html>
