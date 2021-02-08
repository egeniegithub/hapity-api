<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Hapity</title>

</head>

<body bgcolor="#333" style="margin:0; margin-top:10px;">
	<div class="email-wrp" style="max-width:840px; margin:0 auto; background-color: #fff; font-family: 'Open Sans', sans-serif;">
		<table style="width:100%;">
            <tr>
            	<td style="border:1px solid; font-size:16px;padding:5px;">Name</td>
                <td style="border:1px solid; font-size:16px;padding:5px;">{{ $data['name'] }}</td>
            </tr>
            <tr>
                <td style="border:1px solid; font-size:16px;padding:5px;">Email</td>
                <td style="border:1px solid; font-size:16px;padding:5px;">{{ $data['email'] }}</td>
            </tr>
            <tr>
                <td style="border:1px solid; font-size:16px;padding:5px;">Message</td>
                <td style="border:1px solid; font-size:16px;padding:5px;">Dear {{ $data['name'] }}, <br><br> {{ $data['message'] }} <br><br> Regards: hapity</td>
            </tr>
        </table> 
	</div>
</body>
</html>