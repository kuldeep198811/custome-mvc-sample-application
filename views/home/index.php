<div class="container">
        <br />
        
		 
		<?php		
		$_formTempData	=	isset($this->_data['_tempFormData'][0]['form_data'])? unserialize($this->_data['_tempFormData'][0]['form_data']):'';
		
		echo $this->__formStart('/', $_method = 'post', $_formId = 'myForm', $_formClass = 'hide', $_autocomplete = "off", $_enctype = '', $arrAdditionalAttr=['role="form"', 'data-toggle="validator"', 'accept-charset="utf-8"']); ?>
			
        <!-- SmartWizard html -->
        <div id="smartwizard">
            <ul>
                <li><a href="#step-1">Step 1<br /><small>Personal Information</small></a></li>
                <li><a href="#step-2">Step 2<br /><small>Address Information</small></a></li>
                <li><a href="#step-3">Step 3<br /><small>Payment Information</small></a></li>
                <li><a href="#step-4">Step 4<br /><small>Terms and Conditions</small></a></li>
            </ul>

            <div>
				<div style="color:red; width: 100%; display:inline-block">
				<?php
				if(isset($_SESSION[$this->_sessionPrefix]['registration_errors']) && !empty($_SESSION[$this->_sessionPrefix]['registration_errors'])){
					
					foreach ($_SESSION[$this->_sessionPrefix]['registration_errors'] as $_errorArray) {
						echo implode($_errorArray, '<br>');
					}
					
					unset($_SESSION[$this->_sessionPrefix]['registration_errors']);
				}
				?>
				</div>
				
				<div style="color:green; width: 100%; display:inline-block">
				<?php
				if(isset($_SESSION[$this->_sessionPrefix]['registration_success']) && !empty($_SESSION[$this->_sessionPrefix]['registration_success'])){
					
					echo $_SESSION[$this->_sessionPrefix]['registration_success'];
					unset($_SESSION[$this->_sessionPrefix]['registration_success']);
				}
				?>
				</div>
				
                <div id="step-1">
                    <h2>Personal Information</h2>
                    <div id="form-step-0" role="form" data-toggle="validator">
                        <div class="form-group">
                            <label for="first_name">First Name:</label>
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Write your first name" maxlength="32" value="<?php echo isset($_POST['first_name'])? $_POST['first_name']:(isset($_formTempData['first_name'])? $_formTempData['first_name']:""); ?>" required />
                            <div class="help-block with-errors"></div>
                        </div>
						<div class="form-group">
                            <label for="last_name">Last Name:</label>
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Write your last name" maxlength="32" value="<?php echo isset($_POST['last_name'])? $_POST['last_name']:(isset($_formTempData['last_name'])? $_formTempData['last_name']:""); ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>
						<div class="form-group">
                            <label for="last_name">Telephone:</label>
                            <input type="text" class="form-control" name="telephone" id="telephone" placeholder="Write your telephone number" maxlength="32" value="<?php echo isset($_POST['telephone'])? $_POST['telephone']:(isset($_formTempData['telephone'])? $_formTempData['telephone']:""); ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>

                </div>
                <div id="step-2">
                    <h2>Address Information</h2>
                    <div id="form-step-1" role="form" data-toggle="validator">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <textarea class="form-control" name="address" id="address" rows="3" maxlength="200" placeholder="Write your address..." required><?php echo isset($_POST['address'])? $_POST['address']:(isset($_formTempData['address'])? $_formTempData['address']:""); ?></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
						<div class="form-group">
                            <label for="zip_code">Zip Code</label>
                            <input type="text" class="form-control" name="zip_code" id="zip_code" maxlength="16" placeholder="Write your zip code" value="<?php echo isset($_POST['zip_code'])? $_POST['zip_code']:(isset($_formTempData['zip_code'])? $_formTempData['zip_code']:""); ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>
						<div class="form-group">
                            <label for="city">City</label>
                            <input type="text" class="form-control" name="city" id="city" placeholder="Write your city name" value="<?php echo isset($_POST['city'])? $_POST['city']:(isset($_formTempData['city'])? $_formTempData['city']:""); ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div id="step-3">
                    <h2>Payment Information</h2>
                    <div id="form-step-2" role="form" data-toggle="validator">
                        <div class="form-group">
                            <label for="account_owner">Account Owner</label>
                            <input type="text" class="form-control" name="account_owner" id="account_owner" placeholder="Account Owner" value="<?php echo isset($_POST['account_owner'])? $_POST['account_owner']:(isset($_formTempData['account_owner'])? $_formTempData['account_owner']:""); ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>
						<div class="form-group">
                            <label for="iban">IBAN</label>
                            <input type="text" class="form-control" name="iban" id="iban" placeholder="Write IBAN" maxlength="34" value="<?php echo isset($_POST['iban'])? $_POST['iban']:""; ?>" required>
                            <div class="help-block with-errors"></div>
                        </div>						
                    </div>
                </div>
                <div id="step-4" class="">
                    <h2>Terms and Conditions</h2>
                    <p>
                        Terms and conditions: Keep your smile :)
                    </p>
                    <div id="form-step-3" role="form" data-toggle="validator">
                        <div class="form-group">
                            <label for="terms">I agree with the T&C</label>
                            <input type="checkbox" id="terms" data-error="Please accept the Terms and Conditions" required>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>


                </div>
            </div>
        </div>

        <?php echo $this->__formEnd(); ?>

    </div>