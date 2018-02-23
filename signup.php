<?php

include_once 'header.php';

?>

  <div class="container" style="padding-top: 50px;">
  	<div class="row">
  		<h2></h2> 
          
  <form class="form-horizontal" action="includes/signup.inc.php" method="POST">
    <fieldset>
  
    <!-- Form Name -->
    <legend>Registration form</legend>
    
    <!-- user_name-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="textinput">User Name</label>  
      <div class="col-md-4">
      <input id="textinput" name="user_name" placeholder="Insert your User Name" class="form-control   input-md"  required="" type="text">
      <span class="help-block"> </span>
      </div>
    </div>
    
    <!-- password -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="textinput">Password</label>  
      <div class="col-md-4">
      <input type="password" id="textinput" name="password" placeholder="Insert your password" class="form-control input-md "  required="">
      <span class="help-block"> </span>
      </div>
    </div>
    
    <!-- weight-->
    <div class="form-group">
      <label class="col-md-4 control-label" for="textinput">Weight</label>  
      <div class="col-md-4">
      <input id="textinput" name="weight" placeholder="Insert your weight in kilograms" class="form-control input-md"    required="" type="text">
      <span class="help-block"> </span>  
      </div>
    </div>
    
    <!-- age -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="textinput">Age</label>  
      <div class="col-md-4">
      <input id="textinput" name="age" placeholder="Insert your age" class="form-control input-md"    required="" type="text">
      <span class="help-block"> </span>  
      </div>
    </div>

    <!-- Radio -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="textinput"></label>
      <div class="col-md-4">
      <input type="radio" name="gender" value="M" checked><span style="font-size: 20">Male</span> &emsp;
      <input type="radio" name="gender" value="F"><span style="font-size: 20">Female</span>
      <span class="help-block"> </span>  
      </div>
    </div>
    
    
    <!-- Button -->
    <div class="form-group">
      <label class="col-md-4 control-label" for="singlebutton"> </label>
      <div class="col-md-4">
        <button id="singlebutton" name="submit" class="btn btn-primary" type="submit">Sign up</button>
      </div>
    </div>


    
    </fieldset>
  </form>
    
  </div>
</div>
</body>
</html>