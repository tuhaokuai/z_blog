<?php

require dirname(__FILE__).'/../../../zb_system/function/c_system_base.php';
require dirname(__FILE__).'/../../../zb_system/function/c_system_admin.php';

$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('Totoro')) {$zbp->ShowError(48);die();}
 
require $blogpath . 'zb_system/admin/admin_header.php';
?>
<style type="text/css">
.text-config {
	width: 95%
}
</style>
<?php
require $blogpath . 'zb_system/admin/admin_top.php';

?>
  test
<?php
require $blogpath . 'zb_system/admin/admin_footer.php';

RunTime();
?>
