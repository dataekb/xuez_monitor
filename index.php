<?php
$serveraddr = $_SERVER['SERVER_ADDR'];
$xuez_explorer = "xuez.donkeypool.com"; # Explorer to use
$xuez_path = "/root/XUEZ/";
$getreportedblock = shell_exec('curl ' . $xuez_explorer . '/api/getblockcount'); # asking block height to explorer

$getinfo = shell_exec("sudo " . $xuez_path . "/xuez-cli getinfo");
$obj = json_decode($getinfo);
$getbalance = $obj->{'balance'}; // 12345
$version = $obj->{'version'};
$getblockcount = $obj->{'blocks'};
$getconnectioncount = $obj->{'connections'};
$stakingstatus = $obj->{'staking status'};
$difficulty = $obj->{'difficulty'};
$address0 = $cmd = shell_exec("sudo " . $xuez_path . "/xuez-cli getaccountaddress 0");

$getstakingstatus = shell_exec("sudo " . $xuez_path . "/xuez-cli getstakingstatus");
$obj2 = json_decode($getstakingstatus);
$walletunlocked = $obj2->{'walletunlocked'};
$enoughcoins = $obj2->{'enoughcoins'};

$getmasternodestatus = shell_exec("sudo " . $xuez_path . "/xuez-cli getmasternodestatus");

$listtransactions = shell_exec("sudo " . $xuez_path . "/xuez-cli listtransactions");
$obj3 = json_decode($listtransactions);
$tx1 = $obj3->{'category'};

$load1 = shell_exec("uptime | grep -ohe 'load average[s:][: ].*' | awk '{ print $3 }' | sed s/,//g");
$load2 = shell_exec("uptime | grep -ohe 'load average[s:][: ].*' | awk '{ print $4 }' | sed s/,//g");
$load3 = shell_exec("uptime | grep -ohe 'load average[s:][: ].*' | awk '{ print $5 }'");
$loadp1 = $load1 * 100;
$loadp1 = $loadp1.'%';
$loadp2 = $load2 * 100;
$loadp2 = $loadp2.'%';
$loadp3 = $load3 * 100;
$loadp3 = $loadp3.'%';

$uptime = shell_exec('uptime -p'); # system uptime
?>

<!DOCTYPE html>
<html>
<title>XUEZ NODE MONITOR</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
html,body,h1,h2,h3,h4,h5 {font-family: "Raleway", sans-serif}
</style>
<body class="w3-light-grey">

<!-- Top container -->
<div class="w3-bar w3-top w3-black w3-large" style="z-index:4">

  <span class="w3-bar-item w3-right"><b>xuez_monitor</b> | Xuez Core v.<?php print $version;?></span>
</div>

<!-- !PAGE CONTENT! -->
<div class="w3-main" style="margin-top:23px;">

  <!-- Header -->
  <header class="w3-container" style="padding-top:22px">
    <h5><b><i class="fa fa-dashboard"></i> Dashboard</b></h5>
  </header>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>



  <div class="w3-row-padding w3-margin-bottom">
    <div id="status" class="w3-quarter">
    </div>
    <div id="connectioncount" class="w3-quarter">
    </div>
    <div id="blockcount" class="w3-quarter">
    </div>
    <div class="w3-quarter">
      <div class="w3-container w3-dark-grey w3-text-white w3-padding-16">
        <div id="balance" class="w3-right">
        </div>
        <div class="w3-clear"></div>
        <h4>Balance</h4>
      </div>
    </div>
  </div>
  <div class="w3-container">
    <ul class="w3-ul w3-card-4 w3-white">
      <?php if(isset($address0)){echo '<li class="w3-padding-16"><span class="w3-xlarge">Random Address (0) : <tr>' . $address0 . '</tr></li>';}?></span>
    </ul>
  </div>
  <div class="w3-container">
    <br>
    <h5>Staking Status</h5>

    <ul class="w3-ul w3-card-4 w3-white">
      <?php if(isset($walletunlocked) && $walletunlocked == 1){echo '<li class="w3-padding-16"><span class="w3-xlarge">Wallet Unlocked</li>';}elseif(isset($walletunlocked) && $walletunlocked == 0){echo '<li class="w3-padding-16"><span class="w3-xlarge">Wallet Locked</li>';} ?></span>
      <?php if(isset($enoughcoins) && $enoughcoins == 1){echo '<li class="w3-padding-16"><span class="w3-xlarge">Enough Coins</li>';}if(isset($enoughcoins) && $enoughcoins == 0){echo '<li class="w3-padding-16"><span class="w3-xlarge">Not Enough Coins</li>';} ?></span>
      <?php if(isset($stakingstatus)){echo '<li class="w3-padding-16"><span class="w3-xlarge">' . $stakingstatus . '</li>';}?></span>
    </ul>
  </div>
  <br>
  <div class="w3-container">
    <h5>Masternode Status</h5>
    <ul class="w3-ul w3-card-4 w3-white">
      <?php if(isset($walletunlocked) && $walletunlocked == 1){echo '<li class="w3-padding-16"><span class="w3-xlarge">Wallet Unlocked</li>';}elseif(isset($walletunlocked) && $walletunlocked == 0){echo '<li class="w3-padding-16"><span class="w3-xlarge">Wallet Locked</li>';} ?></span>
    </ul>
  </div>
  <br>
  <div class="w3-container">
    <h5>Transactions</h5>
    <table class="w3-table w3-striped w3-bordered w3-border w3-hoverable w3-white">
      <tr>
        <td><b>Date</b></td>
        <td><b>Time (UTC)</b></td>
        <td><b>Category</b></td>
        <td><b>Address</b></td>
        <td><b>Amount</b></td>
      </tr>
      <?php
      for ($i = 0; $i < count($obj3); $i++) {
        echo "<tr>";
        echo "<td>" . date('d/m/Y', $obj3[0]->{'timereceived'}) . "</td>";
        echo "<td>" . date('H:i:s', $obj3[0]->{'timereceived'}) . "</td>";
        echo "<td>" . $obj3[$i]->{'category'} . "</td>";
        echo "<td><a href=http://xuez.donkeypool.com/tx/" . $obj3[$i]->{'txid'} . ">" . $obj3[$i]->{'address'} . "</td>";
        echo "<td>" . $obj3[$i]->{'amount'} . "</td>";
        echo "</tr>";
      }
       ?>
    </table><br>
  </div>
  <div class="w3-container">
    <h5>System Info</h5>

    <p>Host</p>
    <div>
      <div class="w3-container w3-dark-grey w3-padding" ><b><?php echo $_SERVER['SERVER_ADDR'] ?></b></div>
    </div>

    <p>Uptime</p>
    <div>
      <div class="w3-container w3-dark-grey w3-padding" ><b><?php $uptime = shell_exec('uptime -p'); echo $uptime?></b></div>
    </div>

    <p>Load Average (1m / 5m / 15m)</p>
    <div class="w3-dark-grey">
      <div class="w3-container w3-center w3-padding w3-<?php if((int)$loadp1 < 20){echo "green";} elseif((int)$loadp1 < 80){echo "orange";} elseif((int)$loadp1 >= 80){echo "red";}?>" style="width:<?php echo $loadp1?>"><b><?php echo $load1?></b></div>
      <div class="w3-container w3-center w3-padding w3-<?php if((int)$loadp2 < 20){echo "green";} elseif((int)$loadp2 < 80){echo "orange";} elseif((int)$loadp2 >= 80){echo "red";}?>" style="width:<?php echo $loadp2?>"><b><?php echo $load2?></b></div>
      <div class="w3-container w3-center w3-padding w3-<?php if((int)$loadp3 < 20){echo "green";} elseif((int)$loadp3 < 80){echo "orange";} elseif((int)$loadp3 >= 80){echo "red";}?>" style="width:<?php echo $loadp3?>"><b><?php echo $load3?></b></div>
    </div>
  <hr>

  <div class="w3-container w3-dark-grey w3-padding-32">
    <div class="w3-row">
      <div class="w3-container w3-third">
        <h5 class="w3-bottombar w3-border-blue">XUEZ Links</h5>
        <p><a href="http://xuez.donkeypool.com">Explorer</a></p>
        <p><a href="https://discordapp.com/invite/3Yypx4C">Discord</a></p>
        <p><a href="https://xuezcoin.com/">Website</a></p>
      </div>
    </div>
  </div>
  <!-- Footer -->
  <footer class="w3-container w3-padding-16 w3-dark-grey">
    <p>Source code available on <a href="https://github.com/dirtyak/xuez_monitor" target="_blank">GitHub</a></p>
  </footer>

  <script>
    function loadlink(){
      $( "#status" ).load( "status.php" );
      $( "#connectioncount" ).load( "connectioncount.php" );
      $( "#blockcount" ).load( "blockcount.php" );
      $( "#balance" ).load( "balance.php" );
    }

    loadlink(); // This will run on page load
    setInterval(function(){
        loadlink() // this will run after every 5 seconds
    }, 1000);
  </script>


  <!-- End page content -->
</div>

</body>
</html>
