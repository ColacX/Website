<?php
    $clientIp=getenv('REMOTE_ADDR');
    $serverIp=$_SERVER['SERVER_ADDR'];
    $serverTime=date("Y-m-d H:i:s",time());
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en-US" xml:lang="en-US" xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

        <title>ColacX</title>
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

        <style type="text/css">
			.wrapper{
				width: 800px;
				margin: 0px auto;
			}
            .banner{
                text-align: center;
            }
            .menu{
                width: 100%;
                text-align: center;
            }
            .sidemenu{
                width: 33%;
                vertical-align: top;
            }
            .centermenu{
                width: 34%;
                vertical-align: top;
            }
        </style>
    </head>

    <body>
		<div class="wrapper">
			<div class="banner">
				<h1>Welcome To ColacX</h1>
				<p>Netcode testing grounds</p>
			</div>

			<table class="menu">
				<tr>
					<td class="sidemenu">
						<h4>Information</h4>
						<b>Client</b><br/>
						<span><?php echo $clientIp; ?></span><br/>
						<b>Server</b><br/>
						<span><?php echo $serverIp; ?></span><br/>
						<span><?php echo $serverTime; ?></span><br/>
						<span>Visitors:</span><br/>
						<span>Readme</span><br/>
					</td>

					<td class="centermenu">
						<h4>Server Links</h4>
						<a href="http://home.swipnet.se/colacx/">http://home.swipnet.se/colacx/</a><br/>
						<a href="http://colacx/">http://colacx/</a><br/>
						<a href="ftp://colacx/">ftp://colacx/</a><br/>
						<?php echo "<a href='ftp://".$serverIp."/'>http://".$serverIp."/</a>"; ?><br/>
						<a href="/SharedStuff/">SharedStuff</a><br/>
						<a href="/phpmyadmin/index.php">phpMyAdmin</a><br/>                    
					</td>

					<td class="sidemenu">
						<h4>Powered by</h4>
						<span>XAMPP</span><br/>
						<span>Apache</span><br/>
						<span>MySQL</span><br/>
						<span>FileZilla</span><br/>

						<p><a href="http://validator.w3.org/check?uri=referer">
							<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" width="88" height="31"/>
						</a></p>
					</td>
				</tr>

				<tr>
					<td class="sidemenu">
						<h4>Webcode</h4>
						<span>HTML 5</span><br/>
						<span>WebSockets</span><br/>
						<span>CSS</span><br/>
						<span>JavaScript</span><br/>
						<span>JApplet</span><br/>
						<span>PHP</span><br/>
						<span>SQL</span><br/>
						<span>Ajax</span><br/>
					</td>

					<td class="centermenu">
						<h4>Programming</h4>
						<span>C++</span><br/>
						<span>Java</span><br/>
						<span>OpenGL</span><br/>
					</td>

					<td class="sidemenu">
						<h4>High Quality</h4>
					</td>
				</tr>
			</table>
		</div>
    </body>

</html>