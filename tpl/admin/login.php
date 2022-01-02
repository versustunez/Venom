<login>
    <div id="login-background"></div>
    <form id="login" novalidate method="POST" action="/admin/api/login">
        <img class="logo" alt="Be Careful" src="/theme/admin/images/logo.svg">
        <p class="error-message hide">Login Failed</p>
        <input type="hidden" name="REDIRECT_TO" value="NO">
        <v-input
                class="input-group"
                data-label="Username"
                required
                name="USERNAME"
                data-error="Username is required">
        </v-input>
        <v-input
                class="input-group"
                data-label="Password"
                required
                name="PASSWORD"
                type="password"
                data-error="Password is required">
        </v-input>
        <button class="btn btn--primary">
            <span class="btn-ripple"></span>
            <span class="btn__content">Login</span>
        </button>
        <a href="">Forgotten Password? [not active!]</a>
    </form>
</login>
