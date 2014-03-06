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
                  <input type="email" id="login-email" class="input-box" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <input type="password" id="login-password" class="input-box" placeholder="Password"/>
                </div>
                <button type="button" class="btn">Login</button>
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
              <form action="" role="form">
                <div class="form-group">
                  <input type="text" id="signup-name" class="input-box" placeholder="Name"/>
                </div>
                <div class="form-group">
                  <input type="email" id="signup-email" class="input-box" placeholder="Email"/>
                </div>
                <div class="form-group">
                  <input type="text" id="signup-twitter" class="input-box" placeholder="Twitter"/>
                </div>
                <div class="form-group">
                  <input type="text" id="signup-web" class="input-box" placeholder="Website"/>
                </div>
                <div class="form-group">
                  <input type="text" id="signup-phone" class="input-box" placeholder="Phone"/>
                </div>
                <div class="form-group">
                  <input type="password" id="signup-password" class="input-box" placeholder="Password"/>
                </div>
                <button type="button" class="btn">Signup</button>
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