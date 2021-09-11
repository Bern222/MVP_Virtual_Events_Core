<?php

  /*****************************************************************************/
  /* This module allows access to the configuration table in the database      */
  /*****************************************************************************/

  $config = array();

  function loadConfig()
  {
    global $config;
    global $db;

    $config = array();

    if($result = $db->query("SELECT * FROM configuration"))
    {
      for($i=0;$i < $result->num_rows;$i++)
      {
        $c = $result->fetch_assoc();
        $config[$c['name']] = $c['value'];
      }
      $result->close();
    }
  }

  function updateConfig($key,$value)
  {
    global $config;
    global $db;

    $exists = false;

    $q = sprintf("SELECT * FROM configuration WHERE name='%s'",$db->real_escape_string($key));
    if($result = $db->query($q))
    {
      if($result->num_rows > 0)
      {
        $exists = true;
      }
      $result->close();
    }

    if($exists)
    {
      // Update the existing value
      $q = sprintf("UPDATE configuration SET value='%s' WHERE name='%s';",$db->real_escape_string($value),$db->real_escape_string($key));
      $db->query($q);
    }
    else
    {
      // New value
      $q = sprintf("INSERT INTO configuration SET value='%s',name='%s';",$db->real_escape_string($value),$db->real_escape_string($key));
      $db->query($q);
    }
    $config[$key] = $value;
  }   

  function getConfig($key)
  {
    global $config;
    global $db;

    $value = "";

    $q = sprintf("SELECT * FROM configuration WHERE name='%s'",$db->real_escape_string($key));
    if($result = $db->query($q))
    {
      if($result->num_rows > 0)
      {
        $c = $result->fetch_assoc();
        $value = $c['value'];
      }
      $result->close();
    }

    return $value;
  }    
 
?>
