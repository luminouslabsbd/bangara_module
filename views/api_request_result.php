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
                    <a href="<?php echo admin_url('bangara_module/bangara_api/api_request_form'); ?>"
                        class="btn btn-default pull-left display-block mright5">
                        <i class="fa-regular fa-user tw-mr-1"></i>
                        Try Again
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body">

                    <?php if(isset($response) && $response != null){ ?>
                    <div class="form-group" app-field-wrapper="body_data">
                        <label for="body_data" class="control-label">Result</label>
                        <textarea require class="form-control" id="result" name="result" rows="4" cols="50">
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