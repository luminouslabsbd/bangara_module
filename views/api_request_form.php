<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                    </h4>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        
                        <!-- <div class="form-group" app-field-wrapper="api_url">
                            <label for="api_url" class="control-label">API URL</label><span style="color:red">*</span>
                            <input type="text" id="api_url" name="api_url" require class="form-control">
                        </div> -->

                        <!-- <div class="form-group">
                        <label for="method" class="control-label">Method</label><span style="color:red">*</span>
                            <div class="clearfix"></div>
                            <div class="dropdown bootstrap-select bs3 open dropup" style="width: 100%;">
                                <select name="method" class="selectpicker" id="method" data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
                                    <option value="GET">  GET</option>
                                    <option value="POST"> POST</option>
                                    <option value="PUT"> PUT</option>
                                </select>
                            </div>
                        </div> -->
                        
                        <div class="row">

                            <div class="form-group col-md-6" app-field-wrapper="email">
                                <label for="email" class="control-label">Email</label><span style="color:red">*</span>
                                <input type="email" id="email" name="email" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="firstname">
                                <label for="firstname" class="control-label">First Name</label><span style="color:red">*</span>
                                <input type="text" id="firstname" name="firstname" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="lastname">
                                <label for="lastname" class="control-label">Last Name</label><span style="color:red">*</span>
                                <input type="text" id="lastname" name="lastname" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="phonenumber">
                                <label for="phonenumber" class="control-label">Phone Number</label><span style="color:red">*</span>
                                <input type="number" id="phonenumber" name="phonenumber" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="debt_amount">
                                <label for="debt_amount" class="control-label">Debt Amount</label><span style="color:red">*</span>
                                <input type="number" id="debt_amount" name="debt_amount" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="invoice_number">
                                <label for="invoice_number" class="control-label">Invoice Number</label><span style="color:red">*</span>
                                <input type="number" id="invoice_number" name="invoice_number" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="campaign">
                                <label for="campaign" class="control-label">Campaign</label><span style="color:red">*</span>
                                <input type="text" id="campaign" name="campaign" require class="form-control">
                            </div>

                            <div class="form-group col-md-6" app-field-wrapper="company">
                                <label for="company" class="control-label">Company</label><span style="color:red">*</span>
                                <input type="text" id="company" name="company" require class="form-control">
                            </div>

                            <input type="hidden" id="from_system" name="from_system" value="from_system" class="form-control">

                        </div>
                        

                        <!-- <div class="form-group" app-field-wrapper="content_type">
                            <label for="content_type" class="control-label"></label><span style="color:red">*</span>
                            <input type="text" id="content_type" name="content_type" require class="form-control">
                            <input type="text" id="content_type" name="content_type" require class="form-control">
                        </div> -->

                        <!-- <div class="form-group" app-field-wrapper="body_data">
                            <label for="body_data" class="control-label">Body Data - Json</label><span style="color:red">*</span>
                            <textarea placeholder='{"customer_id": "4673","project_id": "111","debt_number": "7648956", "channel": "test"}' required class="form-control" id="body_data" name="body_data" rows="8" cols="50"></textarea>
                        </div> -->


                        

                    </div>
                    
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>

                    <?php if(isset($response) && $response != null){ ?>
                    <div class="form-group" app-field-wrapper="body_data">
                        <label for="body_data" class="control-label">Result</label>
                        
                        <textarea require class="form-control" id="body_data" name="body_data" rows="4" cols="50">
                            <?php  echo ($response != null ? $response : '') ?>
                        </textarea>
                    </div>
                    <?php } ?>

                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

</body>

</html>