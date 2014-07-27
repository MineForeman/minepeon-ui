<?php

require_once('miner.inc.php');
require_once('functions.inc.php');
require_once('settings.inc.php');

include('static/head.php');
include('static/menu.php');

?>
<div class="container">
  <legend>About</legend>
  <img align="right" title="MinePeonQR" alt="Donate to 12Ui8w9q6eq6TxZmow8H9VHbZdfEnsLDsB" src="img/MinePeonQR.png" width="200" height="200" />
    <blockquote class="a">Peon's in feudal times were indentured vassals, often assigned dangerous, tedious and unpleasant work, like
      <cite>MineForeman</cite>
    </blockquote>
  <p>Donate to 12Ui8w9q6eq6TxZmow8H9VHbZdfEnsLDsB</p>
</div>

<div class="container">
  <legend>Contact</legend>
  <div style="padding: 10px 0 0 0;">You can contact Neil directly at;-</div>
  <div style="padding: 10px 0 0 0;">
    <img style="border: 0; float: left;" alt="" src="img/MineFormanEmail.png" />
      <div style="margin-left: 10px; float: left;">
        <div style="padding: 0 0 3px 0;">
          <a href="https://www.facebook.com/MineForeman">
            <img style="border: 0;" alt="Facebook" src="img/facebook.png" />
          </a>
        </div>
        <div style="padding: 0 0 3px 0;">
          <a href="https://twitter.com/mineforeman">
            <img style="border: 0;" alt="Twitter" src="img/twitter.png" />
          </a>
        </div>
        <div style="padding: 0 0 3px 0;">
          <a href="http://www.linkedin.com/company/mineforeman-com">
            <img style="border: 0;" alt="LinkedIn" src="img/linkedin.png" />
          </a>
        </div>
      </div>
      <div style="margin-left: 10px; float: left;">
        <div style="font-weight: bold; font-family: helvetica; font-size: 16px;">Neil Fincham</div>
        <div style="font-family: helvetica; font-size: 12px; color: #999;">MineForeman</div>
        <div style="font-family: helvetica; font-size: 12px; color: #999;">Phone: +64 21 545 583</div>
        <div style="font-family: helvetica; font-size: 12px; color: #999;">C/O Integral LTD, 99 Sala St, Rotorua, New Zealand. 3010</div>
        <div style="font-family: helvetica; font-size: 12px; color: #999;">
          <a style="font-family: helvetica; font-size: 12px; color: #2f97ff; text-decoration: none;" href="http://mineforeman.com/">Website</a> |
          <a style="font-family: helvetica; font-size: 12px; color: #2f97ff; text-decoration: none;" href="mailto:neil@mineforeman.com">neil@mineforeman.com</a>
        </div>
      </div>
    </div>
  </div>
</div>



<div class="container">
      <legend><?php echo $lang["license"]; ?></legend>

<?php
if ($settings['lang'] == "no"){
  include("lang/no/license.no.php");
}else{
  include("lang/en/license.en.php");
}
php?>

</div>
<?php

include('static/foot.php');
