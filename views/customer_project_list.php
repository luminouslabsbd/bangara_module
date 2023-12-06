<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        
    <div class="panel_s">
        <div class="panel-body">
          
          <h4 class="no-margin font-bold"><?php echo $title; ?></h4>
          <hr />
          
          <div class="row">
           
            <div class="clearfix"></div>
          </div>
          <table class="table table-admin-customer-project">
            <thead>
              
              <th>Date</th>
              <th>Customer ID</th>
              <th>Project ID</th>
              <th>Debt Number</th>
              <th>Channel</th>
              
            </thead>
            <tbody>
            
           
    
            </tbody>
          </table>
        </div>
      </div>

    </div>
</div>

<?php init_tail(); ?>

<script>
    $(function(){        
      initDataTable('.table-admin-customer-project', admin_url + 'bangara_module/bangara_api/c_p_table', false, false, false);
    });
</script>

</body>

</html>