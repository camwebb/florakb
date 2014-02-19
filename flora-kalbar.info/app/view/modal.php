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

<script type="text/javascript">
    $(document).ready(function() {		
        $('a#btn-login').click(function(){
           $('div#modal-login').fadeToggle("linear");
        });
        $('.close,.modal-background').click(function(){
           $('div#modal-login').fadeOut(); 
        });
    });
</script>