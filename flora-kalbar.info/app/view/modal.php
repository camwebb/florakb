<!-- LOGIN MODAL -->

<div id="modal-login" class="modal-background">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4>Login</h4>
              <button type="button" class="close">×</button>
              <div class="clear"></div>
            </div>
            <div class="modal-body">
              <form action="" role="form">
                <div class="form-group">
                  <input type="email" id="login-email" class="input-box" name="email" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <input type="password" id="login-password" class="input-box" name="pass" placeholder="Password"/>
                </div>
                <input type="submit" class="btn" style="width: 100px;" value="Signup" />
              </form>
            </div>
            <div class="modal-footer"> 
                Not a member? <a href="#" class="signup">Sign up now!</a><br />
                <a href="#">Forgotten password?</a>
            </div>
        </div>
    </div>
</div>

<!-- LOGIN SIGNUP -->

<div id="modal-signup" class="modal-background">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4>Sign Up</h4>
              <button type="button" class="close">×</button>
              <div class="clear"></div>
            </div>
            <div class="modal-body">
              <form action="<?=$basedomain?>home/signup" method="POST">
                <div class="form-group">
                  <input type="text" id="signup-name" class="input-box" name="name" placeholder="Name" required aria-required="true"/>
                </div>
                <div class="form-group">
                  <input type="email" id="signup-email" class="input-box" name="email" placeholder="Email" required aria-required="true"/>
                </div>
                <div class="form-group">
                  <input type="text" id="signup-twitter" class="input-box" name="twitter" placeholder="Twitter"/>
                </div>
                <div class="form-group">
                  <input type="url" id="signup-web" class="input-box" name="web" placeholder="Website"/>
                </div>
                <div class="form-group">
                  <input type="text" id="signup-phone" class="input-box" name="phone" placeholder="Phone"/>
                </div>
                <div class="form-group">
                  <input type="password" id="signup-password" class="input-box" name="pass" placeholder="Password" required aria-required="true"/>
                </div>
                <div class="form-group">
                  <input type="password" id="signup-re_password" class="input-box" name="re_pass" placeholder="Retype Password" required aria-required="true"/>
                </div>
                <input type="submit" class="btn" style="width: 100px;" value="Signup" />
              </form>
            </div>
            <div class="modal-footer"> 
                Already a member? <a href="#" class="signup">Login now!</a><br />
                <a href="#">Forgotten password?</a>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {		
        $('a#btn-login').click(function(){
           $('div#modal-login').fadeToggle("linear");
        });
        $('a#btn-signup').click(function(){
           $('div#modal-signup').fadeToggle("linear");
        });
        $('.close').click(function(){
           $('div#modal-login,div#modal-signup').fadeOut(); 
        });
    });
</script>