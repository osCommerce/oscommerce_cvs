<?php
  function tep_session_start() {

    return session_start();

  }

  function tep_session_register($variable) {

    return session_register($variable);

  }

  function tep_session_is_registered($variable) {

    return session_is_registered($variable);

  }

  function tep_session_unregister($variable) {

    return session_unregister($variable);

  }

  function tep_session_id($sessid='') {

    if ($sessid) 
       return session_id($sessid);
    else
       return session_id();
      
  }

  function tep_session_name($name='') {

    if ($name)
      return session_name($name);
    else
      return session_name();

  }

  function tep_session_close() {

    if (function_exists('session_close')) {
      return session_close();
    }

  }

  function tep_session_destroy() {

    return session_destroy();

  }
?>