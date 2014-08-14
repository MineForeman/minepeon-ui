<?php

require_once('settings.inc.php');
require_once('miner.inc.php');

// Check for settings to write and do it after all checks
$writeSettings = false;

// Restore

if (isset($_FILES["file"]["tmp_name"])) {
    exec("tar -xzf " . $_FILES["file"]["tmp_name"] . " -C / ");
    header('Location: /reboot.php');
    exit;
}



// User settings
if (isset($_POST['userTimezone'])) {
    
    $settings['userTimezone'] = $_POST['userTimezone'];
    ksort($settings);
    writeSettings($settings);
    header('Location: /settings.php');
    exit;
    
}

if (isset($_POST['lang'])) {
    
    $settings['lang'] = $_POST['lang'];
    $writeSettings    = true;
    
}

if (isset($_POST['userPassword1'])) {

  if ($_POST['userPassword1'] <> '') {
    $hash = crypt($_POST['userPassword1'], base64_encode($_POST['userPassword1']));
    $contents = "minepeon" . ':' . $hash;
    file_put_contents('/opt/minepeon/etc/minepeon.pwd', $contents);
    header('Location: /settings.php');
    exit;
  }
}
// Miner startup file

if (isset($_POST['minerSettings'])) {
    
    if ($_POST['minerSettings'] <> '') {
        
        file_put_contents('/opt/minepeon/etc/init.d/miner-start.sh', preg_replace('/\x0d/', '', $_POST['minerSettings']));
        exec('/usr/bin/chmod +x /opt/minepeon/etc/init.d/miner-start.sh');
    }
}

$minerStartup = file_get_contents('/opt/minepeon/etc/init.d/miner-start.sh');

// Write settings
if ($writeSettings) {
    writeSettings($settings);
}

function formatOffset($offset)
{
    $hours     = $offset / 3600;
    $remainder = $offset % 3600;
    $sign      = $hours > 0 ? '+' : '-';
    $hour      = (int) abs($hours);
    $minutes   = (int) abs($remainder / 60);
    
    if ($hour == 0 AND $minutes == 0) {
        $sign = ' ';
    }
    return $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes, 2, '0');
    
}

$utc = new DateTimeZone('UTC');
$dt  = new DateTime('now', $utc);

$tzselect = '<select id="userTimezone" name="userTimezone" class="form-control">';

foreach (DateTimeZone::listIdentifiers() as $tz) {
    $current_tz = new DateTimeZone($tz);
    $offset     = $current_tz->getOffset($dt);
    $transition = $current_tz->getTransitions($dt->getTimestamp(), $dt->getTimestamp());
    $abbr       = $transition[0]['abbr'];
    
    $tzselect = $tzselect . '<option ' . ($settings['userTimezone'] == $tz ? "selected" : "") . ' value="' . $tz . '">' . $tz . ' [' . $abbr . ' ' . formatOffset($offset) . ']</option>';
}
$tzselect = $tzselect . '</select>';


include('static/head.php');
include('static/menu.php');
?>
<div class="container">
  <h2>Settings</h2>

<!-- ### Begin TimeZone ### -->
<form name="timezone" action="/settings.php" method="post" class="form-horizontal">
  <fieldset>
    <legend>TimeZone</legend>
    <div class="form-group">
      <label for="userTimezone" class="control-label col-lg-3">Timezone</label>
      <div class="col-lg-9">
        <?php echo $tzselect; ?>
        <p class="help-block">MinePeon thinks it is now <?php echo date('D, d M Y H:i:s T'); ?></p>
        <button type="submit" class="btn btn-default">Save</button>
      </div>
    </div>
  </fieldset>
</form>
<!-- ### End TimeZone  ### -->
<!-- ### Begin API  ### -->

<?php

if (isset($_POST['apiEnable'])) {

  if ($_POST['apiEnable'] == "on") {
    $settings['apiEnable'] = true;
  } else {
    $settings['apiEnable'] = false;
  }
  if ($_POST['apiWrite'] == "on") {
    $settings['apiWrite'] = true;
  } else {
    $settings['apiWrite'] = false;
  }
  $settings['apiKey'] = $_POST['apiKey'];
  $settings['apiURL'] = $_POST['apiURL'];

  writeSettings($settings);

}

?>

<form name="api" action="/settings.php" method="post" class="form-horizontal">
  <fieldset>
  <legend>API</legend>
    <div class="form-group">
    <label for="donateAmount" class="control-label col-lg-3">API Permissions</label>
      <div class="col-lg-9">
        <div class="checkbox">
          <input type='hidden' value='false' name='apiEnable'>
          <label>
            <input type="checkbox" <?php if ($settings['apiEnable']) { echo "checked"; } ?> id="apiEnable" name="apiEnable">
            Enable API
          </label>
        </div>
        <div class="checkbox">
          <input type='hidden' value='false' name='apiWrite'>
          <label>
            <input type="checkbox" <?php if ($settings['apiWrite']) { echo "checked"; } ?> id="apiWrite" name="apiWrite">
            Enable API Write access
          </label>
        </div>
      </div>
      <label for="apiKey" class="control-label col-lg-3">API Key</label>
      <div class="col-lg-9">
        <input type="text" id="apiKey" name="apiKey" class="form-control" value="<?php echo $settings['apiKey']; ?>">
      </div>
      <label for="apiURL" class="control-label col-lg-3">API URL</label>
      <div class="col-lg-9">
        <input type="text" id="apiURL" name="apiURL" class="form-control" value="<?php echo $settings['apiURL']; ?>">
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-9 col-offset-3">
        <h4>Experimental API instructions</h4>
        <p>
          If the API is enabled above MinePeon will report to the API server above.  You can <a target="_blank" href="<?php echo $settings['apiURL']; ?>">click here</a> to access the api.  There is not much there yet but it will be improving.
        </p>
        <p>
          If you disable the API MinePeon will no longer report to the api server.
        </p>
        <p>
          API Write will allow the API to make changes to your MinePeon, currently it is disabled and not implemented.  If the API is disabled this will be ignored.
        </p>
        <p>
          The API key is unique to this MinePeon and is generated by the API server when you first report to the API server.  The API server will only recognise keys it has generated and it is unique to each MinePeon.  If you reload your MinePeon you may wish to save the key and use it again (or use the backup below).  If you use it on multiple MinePeon installs all sorts of weirdness will happen and you may get sucked into an alternate dimintion (nearly happened to me twice so far).
        </p>
        <button type="submit" class="btn btn-default">Save</button>
      </div>
    </div>
  </fieldset>
</form>
<!-- ### End API ### -->
<!-- ### Begin Language ### -->
<form name="language" action="/settings.php" method="post" class="form-horizontal">
  <fieldset>
    <legend>Language</legend>
    <div class="form-group">
      <label for="userlanguage" class="control-label col-lg-3">Language</label>
      <div class="col-lg-9">
        <select name="lang" class="form-control">
          <option value="en">English</option>
          <option value="no" <?php if ($settings['lang'] == "no") { echo "selected"; } ?>>Norsk (Norwegian)</option>
        </select>
        <br>
        <button type="submit" id class="btn btn-default">Save</button>
      </div>
    </div>
  </fieldset>
</form>
<!-- ### End Language ### -->

    <form name="password" action="/settings.php" method="post" class="form-horizontal">
    <fieldset>
      <legend>Password</legend>
      <div class="form-group">
        <label for="userPassword" class="control-label col-lg-3">New Password</label>
        <div class="col-lg-9">
          <input type="password" placeholder="New password" id="userPassword1" name="userPassword1" class="form-control" onkeyup="checkPass(); return false;">
                  <br />
                  <input type="password" placeholder="Repeat Password" id="userPassword2" name="userPassword2" class="form-control" onkeyup="checkPass(); return false;">
                  <br />
          <button type="submit" id="submitPassword" class="btn btn-default">Save</button>
        </div>

      </div>

    </fieldset>
  </form>

<!-- ######################## -->

<!-- ######################## -->

  <form name="minerStartup" action="/settings.php" method="post" class="form-horizontal">
    <fieldset>
      <legend>Miner Startup Settings</legend>
      <div class="form-group">
        <label for="minerSettings" class="control-label col-lg-3">Settings</label>
        <div class="col-lg-9">
          <div>
                        <textarea rows="4" cols="120" id="minerSettings" name="minerSettings"><?php
echo $minerStartup;
?></textarea>
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="col-lg-9 col-offset-3">
          <button type="submit" class="btn btn-default">Save</button>
                  <button type="button" type="bfgminer" onclick="minerSwitch('bfgminer')" class="btn btn-default">Default bfgminer</button>
                  <button type="button" type="cgminer" onclick="minerSwitch('cgminer')" class="btn btn-default">Default cgminer</button>
                  <button type="button" type="cgminer" onclick="minerSwitch('cgminer-HEXu')" class="btn btn-default">cgminer-HEXu</button>
                  <script language="javascript" type="text/javascript">
                        function minerSwitch(miner) {
                          if (miner == "cgminer") {
                                document.getElementById('minerSettings').value = "#!/bin/bash\nsleep 10\n/usr/bin/screen -dmS miner /opt/minepeon/bin/cgminer -c /opt/minepeon/etc/miner.conf\n";
                          }
                          if (miner == "bfgminer") {
                                document.getElementById('minerSettings').value = "#!/bin/bash\nsleep 10\n/usr/bin/screen -dmS miner /opt/minepeon/bin/bfgminer -S all -c /opt/minepeon/etc/miner.conf\n";
                          }
                          if (miner == "cgminer-HEXu") {
                                document.getElementById('minerSettings').value = "#!/bin/bash\nsleep 10\n/usr/bin/screen -dmS miner /opt/minepeon/bin/cgminer-HEXu -c /opt/minepeon/etc/miner.conf\n";
                          }
                        }
                  </script>
                  <p class="help-block">
            Enter you own miner parameters or select a default bfgminer or cgminer configuration.
                        You will need to press Save and then reboot MinePeon when you finish.<br />
                        If you intend to enable the cgminer-HEXu option <a href="http://minepeon.com/index.php/Cgminer-HEXu">please read this page for instructions.</a>
          </p>
        </div>
      </div>
    </fieldset>
  </form>

<!-- ######################## -->

<!-- ### Begin Donations ### -->
<?php

// Donation settings

if (isset($_POST['donateEnable'])) {

  if ($_POST['donateEnable'] == "on") {
    $settings['donateEnable'] = true;
  } else {
    $settings['donateEnable'] = false;
  }
  $settings['donateAmount'] = $_POST['donateAmount'];
  $settings['donateTime'] = $_POST['donateTime'];

  writeSettings($settings);

}

?>

<form name="donation" action="/settings.php" method="post" class="form-horizontal">
  <fieldset>
  <legend>Donation</legend>
    <div class="form-group">
    <label for="donateAmount" class="control-label col-lg-3">Donation</label>
      <div class="col-lg-9">
        <div class="checkbox">
          <input type='hidden' value='false' name='donateEnable'>
          <label>
            <input type="checkbox" <?php if ($settings['donateEnable']) { echo "checked"; } ?> id="donateEnable" name="donateEnable">
            Enable donation
          </label>
        </div>
        <div>
          <div class="input-group">
            <span class="input-group-addon">Donate</span>
            <input type="number" value="<?php echo $settings['donateAmount']; ?>" id="donateAmount" name="donateAmount" class="form-control">
            <span class="input-group-addon">minutes per day. </span><span class="input-group-addon"> The Donation will happen At </span>
	    <input type="number" name="donateTime" min="0" max="23" class="form-control" value="<?php echo $settings['donateTime']; ?>" >
            <span class="input-group-addon"><?php echo date('T'); ?></span>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
      <div class="col-lg-9 col-offset-3">
        <button type="submit" class="btn btn-default">Save</button>
      </div>
    </div>
  </fieldset>
</form>
<!-- ### End Donations ### -->
<!-- ######################## -->

  <form name="backup" action="/settings.php" method="post" enctype="multipart/form-data" class="form-horizontal">
    <fieldset>
      <legend>Backup</legend>
     <div class="form-group">
        <div class="col-lg-9 col-offset-3">
                  <a class="btn btn-default" href="/backup.php">Backup</a>
                  <p class="help-block">The backup will contain all of your settings and statistics.  Plugins will have to be restored separately.</p>
        </div>
      </div>
      <div class="form-group">
                <div class="col-lg-9 col-offset-3">
                  <input type="file" name="file" id="file" class="btn btn-default" data-input="false">
                </div>
          </div>
          <div class="form-group">
                <div class="col-lg-9 col-offset-3">
                  <button type="submit" name="submit" class="btn btn-default">Restore</button>
                  <p class="help-block">Restoring a configuration will cause your MinePeon to reboot.</p>
                </div>
      </div>
    </fieldset>
  </form>
<script type="text/javascript" id="js">
  function checkPass()
{
    //Store the password field objects into variables ...
    var pass1 = document.getElementById('userPassword1');
    var pass2 = document.getElementById('userPassword2');
    //Store the Confimation Message Object ...
    var message = document.getElementById('confirmMessage');
        var submit = document.getElementById('submitPassword');
    //Set the colors we will be using ...
    var goodColor = "#66cc66";
    var badColor = "#ff6666";
    //Compare the values in the password field
    //and the confirmation field
    if(pass1.value == pass2.value){
        //The passwords match.
        //Set the color to the good color and inform
        //the user that they have entered the correct password
                document.getElementById("submitPassword").disabled = false;
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Passwords Match!"
    }else{
        //The passwords do not match.
        //Set the color to the bad color and
        //notify the user.
                document.getElementById("submitPassword").disabled = true;
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Passwords Do Not Match!"
    }
} </script>
<?php
include('static/foot.php');

