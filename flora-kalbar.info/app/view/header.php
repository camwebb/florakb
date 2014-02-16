<div class="wrapper">
    <div id="identity">
        <span id="flora">FLORA</span><span id="kalbar"> KALBAR</span>
        <h2>Records of Plants of Kalimantan Barat</h2>
    </div>
    
    <div id="log-btn">
        <a href="register.html" id="btn-reg" class="btn-box">Register</a>
        <a href="#" id="btn-login" class="btn-box">Login</a>
    </div>
    
    <div id="log-box">
        <form>
            <label for="input-username" class="label-login">Username</label>
            <input id="input-username" class="input-box" type="text" name="username" />
            <div class="clear"></div>
            <label for="input-password" class="label-login">Password</label>
            <input id="input-password" class="input-box" type="password" name="password" />
            <div class="clear"></div>
            <input class="btn-box" id="btn-login_submit" type="submit" value="Login" />
        </form>
    </div>
    
    <div class="clear"></div>
</div>

<script type="text/javascript">
    $(document).ready(function() {		
        $('a#btn-login').click(function(){
           $('div#log-box').slideToggle(); 
        });
    });
</script>