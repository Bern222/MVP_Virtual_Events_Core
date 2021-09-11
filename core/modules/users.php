<?php

function userVerifyByEmail($email)
  {
    global $config;
    global $db;
    global $userdata;

    $userdata = array(id=>0);
    $allSales = array();

    if($email == "")
    {
      return(_USER_MISSING);
    }

    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($email));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $userdata = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return(_USER_DBERROR);
    }

    if($userdata['id'] == 0) return(_USER_UNKNOWN);

    return(_USER_VALID);  
  }

  /*****************************************************************************/
  /* This module allows access to the users table in the database              */
  /*****************************************************************************/



  /*****************************************************************************/
  /* Function: userCreate                                                      */
  /*                                                                           */
  /* Description: This function is used to create a new user account in the    */
  /*              system.                                                      */
  /*                                                                           */
  /* Input: $ud - Array of user data in the same format as the user record     */
  /*        in the user database table.                                        */
  /*                                                                           */
  /*        Required fields:                                                   */
  /*         email - User's email address (also used as user name)             */
  /*         firstname = User's first name                                     */
  /*         lastname = User's last name                                       */
  /*                                                                           */
  /*        Optional fields:                                                   */
  /*         password - The desired password. If empty or does not exist,      */
  /*                    a random password will be created.                     */
  /*                                                                           */
  /* Return: The provided $ud array with added fields defined below:           */
  /*                                                                           */
  /*         Added/Updated fields in the returned array:                       */
  /*          password - If a password was generated, the generated password   */
  /*                     will be available in this field in an un-encrypted    */
  /*                     format to facilitate sending in an email.             */
  /*          status -   A return code (see _UCREATE defines below)            */
  /*          id -       The database record id for this user.                 */
  /*****************************************************************************/

  $userdata = array(id=>0);

  define("_UCREATE_SUCCESS",1);      // Account created successfully
  define("_UCREATE_EXISTS",-1);      // Account already exists
  define("_UCREATE_MISSING",-2);     // Data Missing
  define("_UCREATE_DBERROR",-100);   // Data Missing

  function userCreate($ud)
  {
    global $config;
    global $db;

    $ud['id'] = 0;

    if($ud['email'] == "" || $ud['firstname'] == "" || $ud['lastname'] == "")
    {
      $ud['status'] = _UCREATE_MISSING;
      return $ud;
    }

    $exists = 0;

    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($ud['email']));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0) $exists = 1;
      $r->close();
    }
    else
    {
      $ud['status'] = _UCREATE_DBERROR;
      return $ud;
    }
    
    if($exists == 1)
    {
      $ud['status'] = _UCREATE_EXISTS;
      return $ud;
    }

    // Create the account, we use the sets array to add all the values needed.
    $sets = array();
    $sets[] = sprintf("email='%s'",$db->real_escape_string($ud['email']));
    $sets[] = sprintf("firstname='%s'",$db->real_escape_string($ud['firstname']));
    $sets[] = sprintf("lastname='%s'",$db->real_escape_string($ud['lastname']));
    if(isset($ud['company'])) $sets[] = sprintf("company='%s'",$db->real_escape_string($ud['company']));
    if(isset($ud['title'])) $sets[] = sprintf("title='%s'",$db->real_escape_string($ud['title']));
    if($config['user_autovalidate'] == 1) $sets[] = "validated=1"; // Default value for validate is 0

    $pw = "";
    if(isset($ud['password']))
    {
      if($ud['password'] != "") $pw = $ud['password'];
    }

    if($pw == "")
    {
      $pw = createPassword();
    }

    if($config['login_encrypt_pw'] == 1)
    {
      $sets[] = sprintf("password='%s'",password_hash($pw,PASSWORD_DEFAULT));
      $sets[] = "password_encrypted=1";
    }
    else
    {
      $sets[] = sprintf("password='%s'",$db->real_escape_string($pw));
      $sets[] = "password_encrypted=0";
    }

    $ud['password'] = $pw;            // We pass the password back unencrypted
    $ud['password_encrypted'] = 0;    // so it can be used in an email

    $q = "INSERT INTO users SET ".implode(",",$sets);
    if($db->query($q))
    {
      // Query successful
      $ud['id'] = $db->insert_id;
      $ud['status'] = _UCREATE_SUCCESS;
    }
    else
    {
      $ud['status'] = _UCREATE_DBERROR;
    }

    return $ud;
  }

  /*****************************************************************************/
  /* Function: userVerify                                                      */
  /*                                                                           */
  /* Description: This function is used to verify a given user name and        */
  /*              password against the data in the user table                  */
  /*                                                                           */
  /* Input: $username - User's email address used as a user name               */
  /*        $password - Unencrypted user password.                             */
  /*                                                                           */
  /* Return: The result of the attempt to validate the information provided.   */
  /*         Values will be one of the _USER defines below.                    */
  /*****************************************************************************/

  define("_USER_VALID",1);        // Successful validation
  define("_USER_MISSING",-1);     // Either user name or password missing
  define("_USER_UNKNOWN",-2);     // Unknown User
  define("_USER_BADPASSWORD",-3); // Invalid Password
  define("_USER_INVALIDATED",-4); // Account not validated
  define("_USER_DISABLED",-5);    // Account disabled
  define("_USER_RESTRICED_DATE",-6);    // Access Restricted by Date
  define("_USER_DBERROR",-100);   // Database error


  function userVerify($username,$password)
  {
    global $config;
    global $db;
    global $userdata;

    $userdata = array(id=>0);
    $allSales = array();

    if($username == "" || $password == "")
    {
      return(_USER_MISSING);
    }

    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($username));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $userdata = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return(_USER_DBERROR);
    }

    if($userdata['id'] == 0) return(_USER_UNKNOWN);

    if($userdata['disabled'] == 1) return(_USER_DISABLED);

    if($userdata['validated'] == 0) return(_USER_INVALIDATED);

    // Compare the password
    if($userdata['password_encrypted'])
    {
      if(!password_verify($password,$userdata['password'])) return(_USER_BADPASSWORD);
    }
    else
    {
      if($password != $userdata['password']) return(_USER_BADPASSWORD);
      // Encrypt the password
      if(intval($config['login_encrypt_pw']) == 1)
      {
        $pw = password_hash($password,PASSWORD_DEFAULT);

        $q = sprintf("UPDATE users SET password='%s',password_encrypted=1 WHERE id=%d",$pw,$userdata['id']);
        $db->query($q);

        $userdata['password'] = $pw;
        $userdata['password_encrypted'] = 1;
      } 
    }

    // $q = sprintf("SELECT * FROM eShowSalesItemsIncludeAllSales WHERE userid=" . $userdata['id'];
    // if($r = $db->query($q))
    // {
    //   if($r->num_rows > 0)
    //   {
    //     while($row = mysqli_fetch_assoc($r)) {
    //       $allSales[] = $row;
    //     }
    //   }
    //   $r->close();
    // }
    // else
    // {
    //   return(_USER_DBERROR);
    // }
    
    


    // return(_USER_RESTRICED_DATE);

    
    return(_USER_VALID);  
  }
  
  /*****************************************************************************/
  /* Function: createPassword                                                  */
  /*                                                                           */
  /* Description: Create a random password string based on the length given    */
  /*              in the configuration table.                                  */
  /*                                                                           */
  /* Input: None                                                               */
  /*                                                                           */
  /* Return: Returns a string with random password value.                      */
  /*****************************************************************************/

  function createPassword()
  {
    global $config;
    global $db;

    $pwok=0;
    $pw = "";

    $length=$config['login_password_length'];

    // define possible characters
    $possible = "abcdefghjkmnpqrstuvwxyz234567890ABCDEFGHIJKLMNPQRSTUVWXYZ!$_@.,[]()";

    $pw = "";

    // add random characters to id until length is reached
    while(strlen($pw) < $length)
    {
      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
      $pw .= $char;
    }

    return $pw;
  }

  /*****************************************************************************/
  /* Function: userPassword                                                    */
  /*                                                                           */
  /* Description: Change the specified user's password.                        */
  /*                                                                           */
  /* Inputs: $username - User's email address                                  */
  /*         $newpassword - The desired password for the user                  */
  /*         $oldpassword - (Optional) The user's old password. If provided,   */
  /*                        The old password will be verified before changing  */
  /*                        the user's password.                               */
  /*                                                                           */
  /* Return: Returns true on success or false on failure.                      */
  /*****************************************************************************/

  function userPassword($username,$newpassword,$oldpassword="")
  {
    global $config;
    global $db;

    // Load the user record
    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($username));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $ud = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return 'user_doesnt_exist';
    }

    if($ud['id'] == 0) return 'user_doesnt_exist';
    if($ud['disabled'] == 1) return 'user_disabled';
    if($ud['validated'] == 0) return 'user_not_validated';

    // Compare the old password
    if($oldpassword != "")
    {
      if($ud['password_encrypted'])
      {
        if(!password_verify($oldpassword,$ud['password'])) return 'old_password_incorrect';
      }
      else
      {
        if($oldpassword != $ud['password']) return 'old_password_doesnt_match';
      } 
    }
    
    $sets = array();
    if(intval($config['login_encrypt_pw']) == 1)
    {
      $pw = password_hash($newpassword,PASSWORD_DEFAULT);

      $sets[] = sprintf("password='%s'",$pw);
      $sets[] = "password_encrypted=1";
    }
    else
    {
      $sets[] = sprintf("password='%s'",$db->real_escape_string($newpassword));
      $sets[] = "password_encrypted=0";
    }

    $q = sprintf("UPDATE users SET password='%s',password_encrypted=1 WHERE id=%d",$pw,$ud['id']);
    if($db->query($q)) return true;

    return false;
  }

  /*****************************************************************************/
  /* Function: userFieldUpdate                                                 */
  /*                                                                           */
  /* Description: Change user field data in the user record. This function     */
  /*              will not allow changing the user's password.                 */
  /*                                                                           */
  /* Inputs: $username - User's email address                                  */
  /*         $fields   - Array of field => values for fields to update         */
  /*                                                                           */
  /* Return: Returns true on success or false on failure.                      */
  /*****************************************************************************/

  function userFieldUpdate($username,$fields)
  {
    global $config;
    global $db;

    // Load the user record
    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($username));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $ud = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return false;
    }

    if($ud['id'] == 0) return false;
    if($ud['disabled'] == 1) return false;
    if($ud['validated'] == 0) return false;

    $sets = array();
    if(isset($fields['email'])) $sets[] = sprintf("email='%s'",$db->real_escape_string($fields['email']));
    if(isset($fields['firstname'])) $sets[] = sprintf("firstname='%s'",$db->real_escape_string($fields['firstname']));
    if(isset($fields['lastname'])) $sets[] = sprintf("lastname='%s'",$db->real_escape_string($fields['lastname']));
    if(isset($fields['company'])) $sets[] = sprintf("company='%s'",$db->real_escape_string($fields['company']));
    if(isset($fields['title'])) $sets[] = sprintf("title='%s'",$db->real_escape_string($fields['title']));
    if(isset($fields['validated'])) $sets[] = sprintf("validated=%d",intval($fields['validated']));
    if(isset($fields['disabled'])) $sets[] = sprintf("disabled=%d",intval($fields['disabled']));

    if(count($sets) > 0)
    {
      $q = "update users SET ".implode(",",$sets).sprintf(" WHERE id=%d",$ud['id']);
      if($db->query($q))
      {
        // Query successful
        return true;
      }
    }

    return false;
  }

  /*****************************************************************************/
  /* Function: userValidate                                                    */
  /*                                                                           */
  /* Description: Set the validated field on the user's record.                */
  /*                                                                           */
  /* Inputs: $username - User's email address                                  */
  /*         $flag - Value to set, 1 for validated, 0 for not validated.       */
  /*                                                                           */
  /* Return: Returns true on success or false on failure.                      */
  /*****************************************************************************/

  function userValidate($username,$flag)
  {
    global $config;
    global $db;

    // Load the user record
    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($username));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $ud = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return false;
    }

    if($ud['id'] == 0) return false;

    if(count($sets) > 0)
    {
      $q = sprintf("update users SET validated=%d WHERE id=%d",intval($flag),$ud['id']);
      if($db->query($q))
      {
        // Query successful
        return true;
      }
    }

    return false;
  }

  /*****************************************************************************/
  /* Function: userDisabled                                                    */
  /*                                                                           */
  /* Description: Set the validated field on the user's record.                */
  /*                                                                           */
  /* Inputs: $username - User's email address                                  */
  /*         $flag - Value to set, 1 for disabled, 0 for not disabled          */
  /*                                                                           */
  /* Return: Returns true on success or false on failure.                      */
  /*****************************************************************************/

  function userDisabled($username,$flag)
  {
    global $config;
    global $db;

    // Load the user record
    $q = sprintf("SELECT * FROM users WHERE email='%s'",$db->real_escape_string($username));
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $ud = $r->fetch_assoc();
      }
      $r->close();
    }
    else
    {
      return false;
    }

    if($ud['id'] == 0) return false;

    if(count($sets) > 0)
    {
      $q = sprintf("update users SET disabled=%d WHERE id=%d",intval($flag),$ud['id']);
      if($db->query($q))
      {
        // Query successful
        return true;
      }
    }

    return false;
  }

  /*****************************************************************************/
  /* Function: userGetById                                                     */
  /*                                                                           */
  /* Description: Load the user record from the ID                             */
  /*                                                                           */
  /* Inputs: $userid - User record id                                          */
  /*                                                                           */
  /* Return: Returns true on success or false on failure.                      */
  /*****************************************************************************/

  function userGetById($userid)
  {
    global $config;
    global $db;
    global $userdata;

    $ret = 0;

    // Load the user record
    $q = sprintf("SELECT * FROM users WHERE id=%d",$userid);
    if($r = $db->query($q))
    {
      if($r->num_rows > 0)
      {
        $userdata = $r->fetch_assoc();
        $ret = 1;
      }
      $r->close();
    }
    return $ret;
  }



?>