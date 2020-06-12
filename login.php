<?php  
/******************************************************************************/
/*                                                                            */
/*  NCX panel 1.0 - Nathalis Minecraft Server Admin Panel                     */ 
/*                                                                            */
/******************************************************************************/
   session_start(); 

   include 'config/config.php';

   if(isset($_SESSION['username'])) {
      header("Location:index.php"); 
   }

   if(isset($_POST['login'])) {
      if (isset($_POST["user"]) && trim($_POST["user"]) != "") {  $user=trim($_POST["user"]); }
      if (isset($_POST["pass"]) && trim($_POST["pass"]) != "") {  
         $pass=trim($_POST["pass"]);
         $hash=md5($pass); 
      }     

      if ($user == USERNAME && PASSWORD==$hash) {     
         $_SESSION['username']=$user;
         header("Location:index.php");
      } else {
         echo("<span style='color:#ff0000;'>invalid UserName or Password.</span>");        
      }
   }
?>
<html>
<head>
<title>Minecraft Server Login Page</title>
<style type="text/CSS">
*, ::after, ::before {
  box-sizing: border-box;
}

body {
  background-color: #212121;
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
  
  display: table;
  width:100%;
}

h1 {
  font-size: 23px;
}

.form {
  width: 300px;
  padding: 64px 15px 24px;
  margin: 0 auto;
}
.form .control {
  margin: 0 0 24px;
}
.form .control input {
  width: 100%;
  padding: 14px 16px;
  border: 0;
  background: transparent;
  color: #fff;
  font-family: monospace, serif;
  letter-spacing: 0.05em;
  font-size: 16px;
}
.form .control input:hover, .form .control input:focus {
  outline: none;
  border: 0;
}
.form .btn {
  width: 100%;
  display: block;
  padding: 14px 16px;
  background: transparent;
  outline: none;
  border: 0;
  color: #fff;
  letter-spacing: 0.1em;
  font-weight: bold;
  font-family: monospace;
  font-size: 16px;
}

.block-cube {
  position: relative;
}
.block-cube .bg-top {
  position: absolute;
  height: 10px;
  background: #020024;
  background: linear-gradient(90deg, #015d19 0%, #c2f500 37%, #00ff42 94%);
  bottom: 100%;
  left: 5px;
  right: -5px;
  transform: skew(-45deg, 0);
  margin: 0;
}
.block-cube .bg-top .bg-inner {
  bottom: 0;
}
.block-cube .bg {
  position: absolute;
  left: 0;
  top: 0;
  right: 0;
  bottom: 0;
  background: #020024;
  background: linear-gradient(90deg, #015d19 0%, #c2f500 37%, #00ff42 94%)
}
.block-cube .bg-right {
  position: absolute;
  /*background: #020024;*/
  background: #0dce00;
  top: -5px;
  z-index: 0;
  bottom: 5px;
  width: 10px;
  left: 100%;
  transform: skew(0, -45deg);
}
.block-cube .bg-right .bg-inner {
  left: 0;
}
.block-cube .bg .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube .bg-inner {
  background: #212121;
  position: absolute;
  left: 2px;
  top: 2px;
  right: 2px;
  bottom: 2px;
}
.block-cube .text {
  position: relative;
  z-index: 2;
  text-shadow: #000000 2px 2px 2px;
}
.block-cube.block-input input {
  position: relative;
  z-index: 2;
}
.block-cube.block-input input:focus ~ .bg-right .bg-inner, .block-cube.block-input input:focus ~ .bg-top .bg-inner, .block-cube.block-input input:focus ~ .bg-inner .bg-inner {
  top: 100%;
  background: rgba(255, 255, 255, 0.5);
}
.block-cube.block-input .bg-top,
.block-cube.block-input .bg-right,
.block-cube.block-input .bg {
  background: rgba(255, 255, 255, 0.5);
  transition: background 0.2s ease-in-out;
}
.block-cube.block-input .bg-right .bg-inner,
.block-cube.block-input .bg-top .bg-inner {
  transition: all 0.2s ease-in-out;
}
.block-cube.block-input:focus .bg-top,
.block-cube.block-input:focus .bg-right,
.block-cube.block-input:focus .bg, .block-cube.block-input:hover .bg-top,
.block-cube.block-input:hover .bg-right,
.block-cube.block-input:hover .bg {
  background: rgba(255, 255, 255, 0.8);
}
.block-cube.block-cube-hover:focus .bg .bg-inner, .block-cube.block-cube-hover:hover .bg .bg-inner {
  top: 100%;
}

</style>
</head>
<body translate="no">
   
   <div style="width:20vh;height:20vh;background-repeat:no-repeat;background-size:contain;background-image:url('mcserver.png');margin: 8vh auto 0 auto; "></div>
   <div style="width:60vh;height:3vh;margin: 2vh auto 0vh auto;font-size: 3vh;text-align: center;"><?php echo(SERVER_NAME); ?></div>
   <form autocomplete="off" class="form" action="" method="post">
      <div class="control">
         <h1>Sign In</h1>
      </div>
      <div class="control block-cube block-input">
         <input name="user" placeholder="Username" type="text">
         <div class="bg-top">
            <div class="bg-inner"></div>
         </div>
         <div class="bg-right">
            <div class="bg-inner"></div>
         </div>
         <div class="bg">
            <div class="bg-inner"></div>
         </div>
      </div>
      <div class="control block-cube block-input">
         <input name="pass" placeholder="Password" type="password">
         <div class="bg-top">
            <div class="bg-inner"></div>
         </div>
         <div class="bg-right">
            <div class="bg-inner"></div>
         </div>
         <div class="bg">
            <div class="bg-inner"></div>
         </div>
      </div>
      <button type="submit" name="login" value="LOGIN" class="btn block-cube block-cube-hover" >
      <div class="bg-top">
         <div class="bg-inner"></div>
      </div>
      <div class="bg-right">
         <div class="bg-inner"></div>
      </div>
      <div class="bg">
         <div class="bg-inner"></div>
      </div>
      <div class="text">Log In</div>
   </form>
   <div style="bottom:0;right:0;font-size:1.6vh;position:fixed;">NCX panel 1.0 - Nathalis Minecraft Server Admin Panel &nbsp;</div>  
</body>
</html>