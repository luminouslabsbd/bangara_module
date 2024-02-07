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
                        
                        <div class="row">
                            
                            <div class="form-group col-md-12" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Campaign Type</label><span style="color:red">*</span>
                                <select id="campaign_type" name="campaign_type" require class="form-control">
                                    <option value="">Select Campaign Type</option>
                                    <option value="VoiceBot">VoiceBot</option>
                                </select>
                            </div>

                            <div class="form-group col-md-12" app-field-wrapper="campaign">
                                <label for="Campaign" class="control-label">Campaign Name</label><span style="color:red">*</span>
                                <input type="text" id="campaign" name="campaign" require class="form-control">
                            </div>  

                            <div>
                                <button class="apply-button" type="button" id="applyTemplateBtn" style="margin-left: 15px;">Apply Template</button>
                            </div>

                            <div class="form-group col-md-8">
                                
                                <label for="body_data" class="control-label">Body Data - Json</label><span style="color:red">*</span>
                                <textarea required class="form-control" id="body_data" name="body_data" rows="10" cols="50"></textarea>
                            </div>

                            <div class="form-group col-md-4">

                                <label for="body_data" class="control-label">Text Template</label><span style="color:red">*</span>
                                <textarea class="form-control" id="body_data_placeholder"  rows="10" cols="50"></textarea>
                            </div>
   
                                
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
<script>
        
        document.addEventListener("DOMContentLoaded", function() {
        const templateData = {
            "email":"hasnatanha6@gmail.com,",
            "firstname":"Hasnat,",
            "lastname":"Tanha,",
            "phonenumber":"8801854043400,",
            "debt_amount":"100,",
            "campaign":"Moniruz Test List,",
            "company":"MoniruzTestListCompnay"
        };

        // Function to apply template
        function applyTemplate() {
            const bodyDataTextarea = document.querySelector('#body_data_placeholder');
            let templateString = '';

            // Construct template string
            for (const key in templateData) {
                templateString += `${key}: ${templateData[key]}\n`;
            }

            // Set template as placeholder
            bodyDataTextarea.value = templateString;
        }

        // Button click event listener
        const applyTemplateBtn = document.getElementById('applyTemplateBtn');
        applyTemplateBtn.addEventListener('click', applyTemplate);
    });

</script>
</body>

</html>