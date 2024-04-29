<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <style>
        .imageLoad {
            margin-left: auto;
            margin-right: auto;
            width: 300px;
        }
    </style>
    <div class="content">
        <?php echo form_open($this->uri->uri_string(), ['id' => 'article-form']); ?>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="tw-flex tw-justify-between tw-mb-2">
                    <h4 class="tw-mt-0 tw-font-semibold tw-text-neutral-700">
                        <span class="tw-text-lg"><?php echo $title; ?></span>
                    </h4>
                    <a href="<?php echo admin_url('bangara_module/bangara_api/create_campaign'); ?>"
                        class="btn btn-default pull-left display-block mright5">
                        <i class="fa-regular fa-user tw-mr-1"></i>
                        Try Again
                    </a>
                </div>

                <div class="panel_s">
                    <div class="panel-body">
                        <?php if(isset($response) && $response != null){ ?>
                            <div class="form-group" app-field-wrapper="body_data">

                                <div class="imageLoad mb-10">

                                    <!-- Print button -->
                                    <button onclick="printQRCode()" style="position: absolute; top: 5px; right: 5px; background-color: #4CAF50; /* Green */ border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none;  font-size: 14px; margin: 5px; cursor: pointer; border-radius: 5px;">Print</button>
                                    <!-- Download button -->
                                    <button onclick="downloadQRCode()" style="position: absolute; top: 5px; right: 65px; background-color: #008CBA; /* Blue */ border: none; color: white; padding: 5px 10px; text-align: center; text-decoration: none;  font-size: 14px; margin: 5px; cursor: pointer; border-radius: 5px;">Download</button>

                                    <?php
                                        if(isset($response['status']) && $response['status'] == 200 && $response['path'] != null) {
                                            // echo '<button class="mb-2" style="position: absolute; top: 0; right: 0;" onclick="printQRCode()">Print QR Code</button><br>';
                                            echo '<img id="qrCodeImage" src="' . $response['path'] . '" alt="QR Code" style="max-width: 100%;">';
                                        }
                                    ?>
                                </div>
                                
                                <br>
                                <textarea require class="form-control" id="result" name="result" rows="10" cols="50">
                                    <?php  
                                        
                                        echo ($response != null ? json_encode($response,JSON_PRETTY_PRINT) : '') ;
                                        
                                    ?>
                                </textarea>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<?php init_tail(); ?>

</body>

<script>
function printQRCode() {
    var image = document.getElementById('qrCodeImage');
    var windowContent = '<!DOCTYPE html>';
    windowContent += '<html>';
    windowContent += '<head><title>Print QR Code</title></head>';
    windowContent += '<body>';
    windowContent += '<img src="' + image.src + '" style="max-width: 100%;">';
    windowContent += '</body>';
    windowContent += '</html>';
    var printWindow = window.open('', '', 'width=600,height=400');
    printWindow.document.open();
    printWindow.document.write(windowContent);
    printWindow.document.close();
    printWindow.print();
}

function downloadQRCode() {
    var image = document.getElementById('qrCodeImage');
    var a = document.createElement('a');
    a.href = image.src;
    a.target = '_blank';
    a.download = 'QR_Code.png';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

</script>

</html>