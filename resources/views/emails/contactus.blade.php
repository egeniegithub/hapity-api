<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>New Job Opportunity</title>
<style type="text/css">
	@import url('https://fonts.googleapis.com/css?family=Lato');
        @import url('https://fonts.googleapis.com/css?family=Open+Sans');
	@import url('https://fonts.googleapis.com/css?family=Montserrat:400,700');
	@media (max-width:767px){
		.emailbody-content td{
			width: 50%;
		}
		.emailbody-content td img{
			width: 100% !important;
		}
	}
	@media (max-width:640px){
		.emailbody-content td{
			display: block;
			width: 100% !important; 
			text-align:center !important;
		}
		.emailbody-content td img{
			width: 50% !important;
		}
	}
	@media (max-width:480px){
		.emailbody-content td{
			display: block;
			width: 100% !important;
		}
		.emailbody-content td img{
			width: 75% !important;
		}
	}
</style>
</head>

<body bgcolor="#333" style="margin:0;">
	<div class="email-wrp" style="max-width:840px; margin:0 auto; background-color: #fff; font-family: 'Open Sans', sans-serif;">
		<table cellpadding="10px" width="100%" style="text-align: center;">
			<tr>
				<td><img src="{{ asset('assets/images/emails/logo-l.png') }}"></td>
			</tr>
		</table>
		<!-- /Email BODY End -->
		<!-- Email Footer -->
		<table cellpadding="30px" bgcolor="#fff" width="100%" style="font-size:16px; color:#888;">
			<tr>
				<td style="padding:20px;">
                                    <h2>New Job Opportunity</h2>
                                    <p>
                                        Hi, {{ $data['name'] }} <br> 
                                        We are glad to inform you there is job opportunity in your catchment area. 
                                    </p>
                                    <p><strong>What to do!</strong></p>
                                    <p>Simply login to your account and click on the opportunity tab where you’ll find all the jobs regarding to your relevant subject and areas. <a href="">LOGIN</a></p>
				</td>
			</tr>
		</table>
		<!-- /Email Footer End -->
	</div>
</body>
</html>