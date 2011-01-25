<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
<head>
   <?php $this->RenderAsset('Head'); ?>
</head>
<body id="<?php echo $BodyIdentifier; ?>" class="<?php echo $this->CssClass; ?>">
   <div id="Frame">
      <div id="Head">
         <div class="Menu">
            <h1><a class="Title" href="<?php echo Url('/'); ?>"><span><?php echo Gdn_Theme::Logo(); ?></span></a></h1>
			<?php
				$Session = Gdn::Session();
				$this->Menu->ClearGroups();
				$this->Menu->AddLink('Activity', "", '/activity',FALSE,array('class'=>'menu-item MenuActivity',"title"=>T('Activity')));
				if ($this->Menu) {
					$Authenticator = Gdn::Authenticator();
					if ($Session->IsValid()) {
						$Notifications = "";$NotificationsCss = "";
						$Conversations = "";$ConversationsCss = "";
						
						$CountNotifications = $Session->User->CountNotifications;
						$CountConversations = $Session->User->CountUnreadConversations;
						if (is_numeric($CountNotifications) && $CountNotifications > 0){
							$Notifications .= ' <span>'.$CountNotifications.'</span>';
							$NotificationsCss = "HasConversations";
						}
						if (is_numeric($CountConversations) && $CountConversations > 0){
							$Conversations .= ' <span>'.$CountConversations.'</span>';
							$ConversationsCss = "HasUserNotifications";
						}
						$this->Menu->AddLink('User',  $Notifications, '/profile/notifications', array('Garden.SignIn.Allow'), array('class' => 'menu-item MenuInbox '.$NotificationsCss,"title"=>T("Notifications")));
						$this->Menu->AddLink('Inbox',$Conversations, '/messages/all', array('Garden.SignIn.Allow'), array('class' => 'menu-item UserNotifications '.$ConversationsCss,"title"=>T("Messages")));	
					}
					echo $this->Menu->ToString();
				}
			?>
			<div id="HeadNav">
				<?php
						$Form = Gdn::Factory('Form');
						$Form->InputPrefix = '';
						echo 
							$Form->Open(array('action' => Url('/search'), 'method' => 'get')),
							$Form->TextBox('Search'),
							$Form->Button(' ', array('Name' => '')),
							$Form->Close();
				?>
				<div id="HeadControls">
					<?php
						$this->Menu->ClearGroups();
						$this->Menu->HtmlId = "MenuRigth";
						$this->Menu->AddLink('Discussions', T("Discussions"), '/discussions', array('Garden.SignIn.Allow'));
						if ($this->Menu) {
							$this->Menu->AddLink('Dashboard', T('Dashboard'), '/dashboard/settings', array('Garden.Settings.Manage'));

							$Authenticator = Gdn::Authenticator();
							if ($Session->IsValid()) {
								$this->Menu->AddLink('User', T("Profile"), '/profile/{UserID}/{Username}', array('Garden.SignIn.Allow'));
								$this->Menu->AddLink('SignOut', T('Sign Out'), Gdn::Authenticator()->SignOutUrl(), FALSE);
							} else {
								$Attribs = array();
								if (SignInPopup() && strpos(Gdn::Request()->Url(), 'entry') === FALSE)
									$Attribs['class'] = 'SignInPopup';

								$this->Menu->AddLink('Entry', T('Sign In'), Gdn::Authenticator()->SignInUrl(), FALSE, array(), $Attribs);
							}

							echo $this->Menu->ToString();
						}
					?>
				</div>
			</div>
         </div>
      </div>
      <div id="Body" class="clearfix">
         <div id="Content"><?php $this->RenderAsset('Content'); ?></div>
	 	 <div id="Panel">
			<?php
				$Authenticator = Gdn::Authenticator();
				if ($Session->IsValid()) {
					echo '<div class="HomePerfil clearfix">';
					if($Session->User->Photo){
						if (strtolower(substr($Session->User->Photo, 0, 7)) == 'http://' || strtolower(substr($Session->User->Photo, 0, 8)) == 'https://') { 
				            $PhotoUrl = $Session->User->Photo;
				         } else {
				            $PhotoUrl = 'uploads/'.ChangeBasename($Session->User->Photo, 'n%s');
				         }
				         echo Img($PhotoUrl, array('alt' => $Discussion->FirstName,"class"=>"unknow-user"));
					}else{
						echo "<img src=\"http://www.gravatar.com/avatar/".md5($Session->User->Email)."?s=50&amp;d=".urlencode(Asset(Gdn::Config('Plugins.Gravatar.DefaultAvatar', 'plugins/Gravatar/default.jpg'), TRUE))."\" />";
					}
					echo '<p>';
					echo Anchor($Session->User->Name,'/profile/'.$Session->User->UserID.'/'.$Session->User->Name,"username");
					echo '<br />';
					echo Anchor(T("Edit my profile"),'/profile/'.$Session->User->UserID.'/'.$Session->User->Name);
					echo "</p></div>";
				}
				//print_r($Session->User);
			?>
			<?php $this->RenderAsset('Panel'); ?>
		</div>
      </div>
      <div id="Foot">
			<?php
				$this->RenderAsset('Foot');
				//echo Wrap(Anchor(T('Powered by Vanilla'), C('Garden.VanillaUrl')), 'div');
			?>
	   </div>
   </div>
	<?php $this->FireEvent('AfterBody'); ?>
</body>
</html>
