<?php
session_start();
session_destroy();

// Redirectare pagina principala produse:
header('Location: Index.html');
?>