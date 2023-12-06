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
                        
                        <div class="form-group" app-field-wrapper="api_url">
                            <label for="api_url" class="control-label">API URL</label><span style="color:red">*</span>
                            <input type="text" id="api_url" name="url" require class="form-control"  value="<?php echo $api_data !==  null ? $api_data->url : ''; ?>">
                        </div>
                        
                        <div class="form-group" app-field-wrapper="api_key">
                            <label for="api_key" class="control-label">API Key</label><span style="color:red">*</span>
                            <input type="text" id="api_key" name="api_key" require class="form-control"  value="<?php echo $api_data !==  null ? $api_data->api_key : '' ; ?>">
                        </div>

                        <input type="hidden" name="id" , value="<?php  echo $api_data !==  null ? $api_data->id: '' ;  ?>">

                        <div class="form-group">
                        <label for="api_key" class="control-label">Status</label><span style="color:red">*</span>
                            <div class="clearfix"></div>
                            <div class="dropdown bootstrap-select bs3 open dropup" style="width: 100%;">
                                <select name="status" class="selectpicker" id="status" data-width="100%" data-none-selected-text="Nothing selected" tabindex="-98">
                                    <!-- <option value=""></option> -->
                                    <option <?php echo $api_data !== null && $api_data->status == 0 ? 'selected' : ''; ?> value="0">Disable</option>
                                    <option <?php echo $api_data !== null && $api_data->status == 1 ? 'selected' : ''; ?> value="1">Active</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    
                    <div class="panel-footer text-right">
                        <button type="submit" class="btn btn-primary">
                            <?php echo _l('submit'); ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

</body>

</html>