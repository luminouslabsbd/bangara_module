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

    .wrapper-spinner-form{
        display: flex;
        gap: 32px;
    }

    .spinner-fomr-col{
        width: 40%;
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


        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                    </h4>

                    <button type="button" class="btn btn-primary campaingn-modal" data-toggle="modal" data-target="#exampleModal">
                        API Setting
                    </button>
                </div>

                

                <div class="panel_s">
                    <div class="panel-body">
                        
                        <div class="row">
                            <div class="form-group col-md-12" app-field-wrapper="campaign_type">
                                <label for="email" class="control-label">Campaign Type</label><span style="color:red">*</span>
                                <select id="campaign_type" name="campaign_type" require class="form-control">
                                    <option value="">Select Campaign Type</option>
                                    <option value="VoiceBot">Tap To Win</option>
                                </select>
                            </div>

                            <div class="form-group col-md-10" app-field-wrapper="campaign">
                                <label for="Campaign" class="control-label">Campaign Name</label><span style="color:red">*</span>
                                <input type="text" id="_name" name="campaign_name" require class="form-control">
                            </div> 

                            <div class="form-group col-md-10">
                                <div class="title">
                                    <button type="button" class="btn btn-success" id="add-input">+ Add Input</button>
                                </div>

                                <div class="spinner-form-wrapper">
                                    <div class="wrapper-spinner-form">
                                        <div class="spinner-fomr-col">
                                            <h5>Fields Name</h5>
                                            <input type="text" name="fields_name[]" class="dynamic-input form-control" />
                                        </div>
                                        <div class="spinner-fomr-col">
                                            <h5>Fields Value</h5>
                                            <input type="text" name="fields_value[]" class="dynamic-input form-control" />
                                        </div>
                                    </div>
                                    <div class="spinner-form" id="input-container"></div>
                                    
                                </div>

                                <div class="spinner-form" id="input-container"></div>
                                
                            </div>

                            </div>

                            <input type="hidden" id="from_system" name="from_system" value="from_system" class="form-control">


                        </div>

                        <div id="inputFieldsContainer">
                                <!-- Input fields will be appended here -->
                        </div>

                            

                    </div>

                <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
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
            var newInput = `<div class="spinner-form-wrapper">
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

</script>
</body>

</html>