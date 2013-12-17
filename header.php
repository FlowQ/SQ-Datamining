<?php if ($user): ?>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Facebook Dashboard</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li id="home_click" class="active"><a href="#">Home</a></li>
            <li id="gender_click" ><a href="#">Gender</a></li>
            <li id="school_click" ><a href="#">School</a></li>
            <li id="birthday_click" ><a href="#">Birthday</a></li>
            <li id="friendsstats_click" ><a href="#">Friends Stats</a></li>
            <li id="ratiopost_click" ><a href="#">Ratio Post</a></li>
            <li id="wallpost_click" ><a href="#">Wall Post</a></li>
            <li id="relation_click" ><a href="#">Relationships</a></li>

            <li><a href="<?php echo $facebook->getLogoutUrl(); ?>">Logout</a></li>
            <li><a href="#contact">Contact</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <?php else: ?> 
    
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Facebook Dashboard</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="<?php echo $loginUrl; ?>">Login with Facebook</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
    <?php endif ?>