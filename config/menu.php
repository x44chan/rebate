<!-- Static navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div style = "float: bottom" class="navbar-header">
        	<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        		<span class="sr-only">Toggle navigation</span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
        		<span class="icon-bar"></span>
        	</button>
        	<a class="navbar-brand" href="/rebate"><span class="icon-ticket" style = "color: #009999;"></span> Rebate </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
        	<ul class="nav navbar-nav navbar-left">
                <li><a  role = "button" href="/rebate"><span class="icon-home3" style = "font-weight: bold;"></span> Home</a></li>
                <?php if($access->level >= 1){ ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-coin-dollar"></span> Rebate <b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a role = "button" href = "rebate"><span class="icon-plus"></span> New Rebate </a></li>
                        <li><a role = "button" href = "rebate/list"><span class="icon-list"></span> Rebate List </a></li>
                    </ul>
                </li>
                <?php } ?>
                <?php if($access->level > 1){ ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <span class="icon-cogs"></span> System Management <b class="caret"></b></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a role = "button" href = "sys/audit"><span class="icon-clipboard"></span> Audit Log </a></li>
                    </ul>
                </li> 
                <?php } ?>
            </ul>
        	<ul class="nav navbar-nav navbar-right">
        		<li class="dropdown">
            		<a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="icon-user"></span> <?php echo $_SESSION['name']; ?> <b class="caret"></b></a>
            		<ul class="dropdown-menu" role="menu">            			
						<li><a role = "button" data-toggle="modal" data-target="#changepass"><span class="icon-eye"></span> Change Password </a></li>
                		<li><a style = "color: red;" role = "button" href = "logout"><span class="icon-switch"></span> Log Out </a></li>					
            		</ul>
            	</li> 
        	</ul>
        </div>
      </div>
    </nav>