<?php
session_start();
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1200)) {
// last request was more than 10 minutes ago
session_unset();     // unset $_SESSION variable for the run-time
session_destroy();   // destroy session data in storage
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp

if(session_destroy()) //Destroying all sessions
{
  echo "<script>alert(`You have successfully signed out from THEADEMOLA's Super Admin Dashboard.`)
location.href='../reg/staff-login.php'</script>";
   //Redirecting to login page
}
?>