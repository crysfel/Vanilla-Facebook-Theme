<?php if (!defined('APPLICATION')) exit(); ?>
<div class="Box GuestBox">
   <h4><?php echo T('Howdy, Stranger!'); ?></h4>
   <div class="clearfix">
		<?php echo Img("themes/tweetmylove/design/images/novios.jpg",array("class"=>"Howdy-image"));?>
   		<p><?php echo T($this->MessageCode, $this->MessageDefault); ?></p>
   </div>
   <?php $this->FireEvent('BeforeSignInButton'); ?>
   <p>
      <?php 
      echo Anchor(T('Sign In'), Gdn::Authenticator()->SignInUrl($this->_Sender->SelfUrl), 'Button'.(SignInPopup() ? ' SignInPopup' : ''));
      $Url = Gdn::Authenticator()->RegisterUrl($this->_Sender->SelfUrl);
      if(!empty($Url))
         echo ' '.Anchor(T('Apply for Membership'), $Url, 'Button');
      ?>
   </p>
   <?php $this->FireEvent('AfterSignInButton'); ?>
</div>