<?php
	include("connect.php");
	include("getpage.php"); 
	

	$result=mysqli_query($conn, "SELECT * FROM `podaci`");
	include_once 'header.php';

?>


  <div id="graph2" class="container" style="margin-top: 200px;">
  	<div class="row align-items-start">
  		<div class="col-sm">
        <?php  include 'getdata2.php';
        		include 'bargraph.js'; ?>
    	</div>
    </div>
  </div>

</body>
</html>