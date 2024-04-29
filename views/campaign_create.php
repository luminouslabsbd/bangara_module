<style>
    .spinner-form{
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 5px;
        align-items: center;
        flex-wrap: wrap;
    }

    .spinner-form-wrapper{
        width: 100%;
    }
    .spinner-form-wrapper-new{
        width: 40%;
    }

    .wrapper-spinner-form{
        display: flex;
        gap: 32px;
    }

    .wrapper-spinner-form-new{
        display: flex;
        gap: 0px;
    }

    .spinner-fomr-col{
        width: 100%;
    }

    .panel-footer.text-right{
        background-color: transparent;
        border: unset;
        padding: 0px;
    }

    button.remove-input.btn.btn-danger{
        padding: 20px 25px;
        margin: 0;
        display: flex;
        justify-content: center;
        height: 0;
        align-items: center;
        margin-bottom: -31px;
    }
    .input-group.apply-main-wrapper {
        display: flex;
        align-items: center;
    }

    .apply-main-wrapper{
        display: flex;
        gap: 20px;
    }

    button.btn.btn-primary.campaingn-modal{
        margin-bottom: 20px;
    }

    .btn.btn-default.campaingn-modal {
        margin-bottom: 20px;
    }

    .btn.btn-success.campaingn-modal {
        margin-bottom: 20px;
    }

</style>
<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">

   

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">API Setting</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="apiForm" action="<?php echo  admin_url('bangara_module/bangara_api/create_campaign_api_setting') ?>" method="post">
                <div class="modal-body">
                    <div class="wrapper-spinner-form">
                        <div class="col-md-12">
                            <h5>Api Url</h5>
                            <input type="text" name="url" require class="dynamic-input form-control" />
                        </div>
                        <div class="col-md-12">
                            <h5>Api Key</h5>
                            <input type="text" name="api_key" class="dynamic-input form-control" />
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>

            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="exampleModalRequired" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Required Keys List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="apiForm" action="" method="">
                <div class="modal-body">
                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>TenantID</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (Tenant ID get from patner account)</h6>
                        </div>
                    </div>
                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>CampaignID</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (CampaignID get from campaign managment module)</h6>
                        </div>
                    </div>

                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>ProductID</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (ProductID is user purchess products id)</h6>
                        </div>
                    </div>

                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>PurchaseValue</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (PurchaseValue is user purchess amount)</h6>
                        </div>
                    </div>

                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>email</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Its Not Required - (Customer email id)</h6>
                        </div>
                    </div>

                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>phone</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (Customer whatsapp phone number)</h6>
                        </div>
                    </div>

                    <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>OrderID</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (Customer purchess Order or Invoice id)</h6>
                        </div>
                    </div>

                    <!-- <div class="wrapper-spinner-form-new">
                        <div class="col-md-12">
                            <h6>is_login</h6>
                        </div>
                        <div class="col-md-12">
                            <h6>Required - (true)</h6>
                        </div>
                    </div> -->

                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>

            </form>
        </div>
    </div>
</div>


        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2 align-items-center">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                    </h4>

                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-default  campaingn-modal" style="" 
                                onclick="window.location='<?php echo admin_url('bangara_module/bangara_api/reset_data'); ?>'">
                            Reset Data
                        </button>

                        <button type="button" class="btn btn-success campaingn-modal" data-toggle="modal" data-target="#exampleModal">
                            API Setting
                        </button>

                        <button type="button" class="btn btn-primary campaingn-modal campaingn-modal-required" data-toggle="modal" data-target="#exampleModalRequired">
                            Required Keys
                        </button>
                    </div>
                </div>

                <?php
                    $old_form_data =  $_SESSION['old_form_data'] ?? null;
                    $old_form_data =  json_decode( $old_form_data ,true);
                ?>
                
                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">

                            <div class="form-group col-md-10" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Campaign Type</label><span style="color:red">*</span>
                                <select id="campaign_type" name="campaign_type" require class="form-control">
                                    <option value="VoiceBot">Tap To Win</option>
                                </select>
                            </div>

                            <?php if(is_client_logged_in()) {?>
                            <div class="form-group col-md-10" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Campaign Name</label><span style="color:red">*</span>
                                <select id="CampaignID" name="CampaignID" require class="form-control">
                                    <?php if($loyality_api_data != null && isset($loyality_api_data['data'])) {
                                        
                                        foreach($loyality_api_data['data']['campagins'] as $campaign) {
                                            $selected = isset($old_form_data['CampaignID']) && $old_form_data['CampaignID'] == $campaign['id'] ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $campaign['id'] ?>" <?php echo $selected ?>><?php echo $campaign['name'] . '  -  ' . $campaign['card_id'] ?></option>
                                        <?php }
                                    }?>
                                </select>
                                <input type="hidden" name="TenantID" id="TenantID" value="<?php  if( $loyality_api_data != null && isset($loyality_api_data['data']) )  echo $loyality_api_data['data']['partnerId'] ?>">
                            </div>
                            <?php }?>

                            <?php if(is_staff_logged_in()) {?>

                            <div class="form-group col-md-10" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Partner Name</label><span style="color:red">*</span>
                                <select id="partnerID" name="partnerID" require class="form-control">
                                    <?php if($all_partner != null && isset($all_partner['data'])) {
                                        foreach($all_partner['data'] as $partner) {
                                            $selected = isset($old_form_data['partnerID']) && $old_form_data['partnerID'] == $partner['id'] ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $partner['id'] ?>" <?php echo $selected ?>><?php echo $partner['name'] . ' - '. $partner['id'] ?></option>
                                        <?php }
                                    }?>
                                </select>
                            </div>

                            <div class="form-group col-md-10" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Campaign Name</label><span style="color:red">*</span>
                                <select id="CampaignID" name="CampaignID" required class="form-control">
                                    <!-- Campaign options will be populated dynamically by JavaScript -->
                                </select>
                                <input type="hidden" name="TenantID" id="TenantID" value="">
                            </div>

                            
                            <?php }?>

                            
                            <div class="form-group col-md-10">

                                <div class="spinner-form-wrapper">

                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Phone Number <span>*</span></h5>
                                            <input type="text" id="phone" value="<?php echo isset($old_form_data['phone']) ? $old_form_data['phone'] : ''; ?>" require name="phone"  placeholder="Enter Customer Phone Number With Country Code" class="dynamic-input form-control" />
                                        </div>
                                    </div>

                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Product ID <span>*</span></h5>
                                            <input type="text" id="ProductID" value="<?php echo isset($old_form_data['ProductID']) ? $old_form_data['ProductID'] : ''; ?>" require name="ProductID" placeholder="Enter Product ID" class="dynamic-input form-control" />
                                        </div>
                                    </div>

                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Purchase Value<span>*</span></h5>
                                            <input type="number" id="PurchaseValue" value="<?php echo isset($old_form_data['PurchaseValue']) ? $old_form_data['PurchaseValue'] : ''; ?>" require name="PurchaseValue" placeholder="Enter Customer Purchase Value" class="dynamic-input form-control" />
                                        </div>
                                    </div>

                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Order ID<span>*</span></h5>
                                            <input type="text" id="OrderID" value="<?php echo isset($old_form_data['OrderID']) ? $old_form_data['OrderID'] : ''; ?>" require name="OrderID" placeholder="Enter Customer Order ID or Invoice ID" class="dynamic-input form-control" />
                                        </div>
                                    </div>

                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Email</h5>
                                            <input type="email" value="<?php echo isset($old_form_data['email']) ? $old_form_data['email'] : ''; ?>" name="email" placeholder="Enter Customer Email" class="dynamic-input form-control" />
                                        </div>
                                    </div>

                                    <div class="spinner-form" id="input-container"></div>
                                </div>
                                
                                <div class="spinner-form" id="input-container"></div>
                                
                            </div>

                            </div>

                        </div>

                        <div id="inputFieldsContainer">
                                <!-- Input fields will be appended here -->
                        </div>

                            

                    </div>

                <div class="panel-footer text-right">
                        <button type="submit" id="submitBtn" class="btn btn-primary"> Send</button>
                </div>
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
<!-- Include jQuery -->

<script>

$(document).ready(function () {
        // Add Input
        $("#add-input").on("click", function () {
            var newInput = `<div class="spinner-form-wrapper-new">
                        <div class="spinner-form">
                            <div class="spinner-fomr-col">
                                <h5>Fields Name</h5>
                                <input type="text" name="fields_name[]" class="dynamic-input form-control" />
                            </div>
                            <div class="spinner-fomr-col">
                                <h5>Fields Value</h5>
                                <input type="text" name="fields_value[]" class="dynamic-input form-control" />
                            </div>
                            <button type="button" class="remove-input btn btn-danger">Remove</button>
                        </div>
                        <div class="spinner-form" id="input-container"></div>
                        
                    </div>
                    `;
            $("#input-container").append(newInput);
        });

        // Remove Input
        $("#input-container").on("click", ".remove-input", function () {
            $(this).parent().remove();
        });
    });

    $(document).ready(function(){
        $('#exampleModal').on('show.bs.modal', function (e) {
            $.ajax({
                type: 'GET',
                url: '<?php echo admin_url('bangara_module/bangara_api/get_api_data') ?>',
                dataType: 'json',
                success: function(data){
                    if(data !== false){
                        $('#apiForm input[name="url"]').val(data.url);
                        $('#apiForm input[name="api_key"]').val(data.api_key);
                    } else {
                        // Handle if data is not found
                    }
                }
            });
        });
    });

   
    // Function to check if all required inputs are filled
    function checkInputs() {
        const inputs = document.querySelectorAll('#CampaignID, #phone, #ProductID, #PurchaseValue, #OrderID');
        for (let input of inputs) {
            if (input.value.trim() === '') {
                return false;
            }
        }
        return true;
    }

    // Function to update the button state
    function updateButtonState() {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = !checkInputs();
    }

    // Add event listeners to input fields
    const inputFields = document.querySelectorAll('#CampaignID, #phone, #ProductID, #PurchaseValue, #OrderID');
    inputFields.forEach(input => {
        input.addEventListener('input', updateButtonState);
    });

    // Initially disable the button
    document.addEventListener('DOMContentLoaded', updateButtonState);


    // API CALL 
    document.addEventListener('DOMContentLoaded', function() {
        const partnerSelect = document.getElementById('partnerID');
        const campaignSelect = document.getElementById('CampaignID');

        partnerSelect.addEventListener('change', function() {
            const partnerID = this.value;
            let endpoint = "<?php echo $url ?>";
            // Make an API request
            fetch(endpoint + 'v1/ll/crm/get-partner/' + partnerID)
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Log the response data for debugging
                    
                    // Clear existing options
                    campaignSelect.innerHTML = '';

                    const tenantIDInput = document.getElementById('TenantID');
                    tenantIDInput.value = data.data.partner.id;

                    // Populate campaign options
                    data.data.campagins.forEach(campaign => {
                        const option = document.createElement('option');
                        option.value = campaign.id;
                        option.textContent = campaign.name + ' - ' + campaign.card_id; // Customize as per your requirement
                        campaignSelect.appendChild(option);
                    });
                })
                
                .catch(error => {
                    console.error('Error fetching campaign data:', error);
                });
        });
    });



</script>
</body>

</html>