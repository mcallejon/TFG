<link rel="stylesheet" href="recursos/login.css">

<?php 

error_reporting(E_ALL ^ E_NOTICE);

$usuario = 'root';
$password = 'root';

if ( $_POST['usuario'] !== $usuario || $_POST['password'] !== $password ) { ?>

</br>
</br>
</br>
</br>
<form class="login" name="form" method="post" action="">
	<p><input type="text"  class="login-input" placeholder="Usuario"  title="usuario" name="usuario" /></p>
	<p><input type="password" class="login-input" placeholder="Contraseña" title="password" name="password" /></p>
	<p><input type="submit" class="login-input" name="submit" value="Login" /></p>
</form>

<?php } else {

	header ("Location: front.php"); 

} ?>


