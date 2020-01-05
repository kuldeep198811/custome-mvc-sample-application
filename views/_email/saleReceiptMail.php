<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sale Receipt</title>
	
	<!-- Roboto Font CSS -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
  </head>
  <body style="background:#eee; line-height: 1.5;">
	<table width="80%" style="margin:0 auto; font-family:Arial;">
		<tr>
			<td></td>
			<td style="text-align:center; padding:20px;"><img src="images/logo.png"></td>
			<td></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td style="background:#fff; padding:50px;">
				<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="font-size:14px;">
                    	<div style="margin-bottom:20px;"><strong>Bill to:</strong><br>
                        	<name><br>
                            <address>,<br>
                            <span><bcity></span>, <span><bstate></span>,<br>
                            <span><bcountry></span>, <span><bpin></span>,<br>
                            <span><bcontatcNo.></span>
                        </div>
                    </td>
                    <td style="text-align:right; font-size:14px;">
                    	<div style="margin-bottom:20px;"><strong>Ship to:</strong><br>
                        	<name><br>
                            <address>,<br>
                            <span><scity></span>, <span><sstate></span>,<br>
                            <span><scountry></span>, <span><spin></span>,<br>
                            <span><scontatcNo.></span>
                        </div>
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2">
                    	<table width="100%" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th colspan="2" style="vertical-align: bottom;border-bottom: 2px solid #dee2e6; border-top: 2px solid #dee2e6;text-align: left;padding: 10px; font-size: 14px;">Item</th>
                                <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;text-align: left; border-top: 2px solid #dee2e6; padding: 10px; text-align:right;font-size: 14px;">Price</th>
                                <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;text-align: left; border-top: 2px solid #dee2e6; padding: 10px; text-align:right;font-size: 14px;">Quantity</th>
                                <th style="vertical-align: bottom;border-bottom: 2px solid #dee2e6;text-align: left; border-top: 2px solid #dee2e6; padding: 10px; text-align:right;font-size: 14px;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                        
                        <tr>
                            <td style="padding: 10px;width: 100px; border-bottom: 1px solid #dee2e6;">
                                <img src="http://10.16.70.70/SAP-STORE/hpi_store/cid-sap-store-discontinued-qa/assets/images/product_images/24.jpg" width="100" title=""  alt="">
                            </td>
                            <td style="padding: 10px; border-bottom: 1px solid #dee2e6;">
                            	<h5 style="margin:5px 0;"><a href="#" style="font-size: 17px; color: #444; text-decoration: none;">Women's V-Neck Top</a></h5>
                                <p style="margin: 5px 0; font-size: 13px; color:#444;">Sku: <b>6188X</b> &nbsp; Color: <b>Green</b> &nbsp; Size: <b>S</b></p>
                            </td>
                            
                            <td style="text-align:right;padding: 10px; font-size: 13px; color:#444; border-bottom: 1px solid #dee2e6;">$6.35</td>
                            <td style="text-align:right;padding: 10px; font-size: 13px; color:#444; border-bottom: 1px solid #dee2e6;">1</td>
                            <td style="text-align:right;padding: 10px; font-size: 13px; color:#444; border-bottom: 1px solid #dee2e6;">$6.35</td>
                        </tr>
                    </tbody>
            </table>

                    </td>
                  </tr>
                  <tr>
                    <td style="text-align:left; vertical-align:top; padding:20px 0">
                    	<div>
  						<label style="display:block; margin-bottom:5px; font-size:14px;"><b>Shippipng Method</b></label>
  						<span name="shippipng_method" style="width:50%; padding: .375rem .75rem; font-size: 1rem; line-height: 1.5; color: #495057; background-color: #fff;border: 1px solid #ced4da; border-radius: .25rem;transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
						<shippingMethod>
                        </select>
  						</div><br>

                        <div>
                            <ul style="padding-left:15px; font-size:14px;">
                                <li>Due to high demand, please allow an additional 48 hours for order processing.</li>
								<li>Credit card charge will appear as "BAMKO" on your statement.</li>
								<li>We will process your order within 48 hours.</li>
								<li>You can manage or view your orders by clicking on your user profile.</li>
								<li>If you require further assistance with an order, please use the feedback section.</li>
                            </ul>
                        </div>
                    </td>
                    <td style="text-align:right; font-size:14px; vertical-align:top; padding:20px 0;">
                    	<div>Subtotal :<span>$20.30</span> </div>
                        <div>Shipping : $<span>0.00</span></div>
                        <div>Tax : $<span>0.00</span></div>
                        <div>Discount : $<span>0.00</span></div>
                        <div><h5 style="font-size:20px;">Order Total : $<span>20.30</span></h5></div>
                        <!--<a href="#" style="background: #d89300;color: #fff;font-size: 15px;padding: 10px 20px !important;text-transform: uppercase;border-radius: 0;border: 1px solid transparent;width: 20%;font-weight: 600;">Confirm</a>-->
                    </td>
                  </tr>
                </table>
			</td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td style="text-align:center; padding:40px;">
				<h5>Stay in Touch<h5>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/facebook.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/twitter.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/insta.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/linked-in.png" style="margin:5px;"></a>
				<a href="#"><img src="<?php echo $this->_assetRoot;?>images/youtube.png" style="margin:5px;"></a>
			</td>
			<td></td>
		</tr>
	</table>
  </body>
</html>