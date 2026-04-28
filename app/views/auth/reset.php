<form method="POST" action="index.php?action=resetPassword">
    <input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
    <input type="password" name="password" placeholder="Nueva contraseña" required>
    <button type="submit">Cambiar</button>
</form>