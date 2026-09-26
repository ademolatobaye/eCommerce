<?php
session_start();
session_unset();
session_destroy();
echo "<script>alert('Successfully signed out.');
window.location.href='index';</script>";
?>