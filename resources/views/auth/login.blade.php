<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>Login</title>
    <link href="./css/tabler.min.css" rel="stylesheet"/>
    <!-- <link href="/css/tabler-flags.min.css" rel="stylesheet"/>
    <link href="/css/tabler-payments.min." rel="stylesheet"/>
    <link href="/css/tabler-vendors.min.css" rel="stylesheet"/> -->
    <link href="./css/demo.min.css" rel="stylesheet"/>
    <style>
      @import url('https://rsms.me/inter/inter.css');
      :root {
      	--tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
      }
      body {
      	font-feature-settings: "cv03", "cv04", "cv11";
      }

      .errormsg{
        background-color: red;
        color: white;
        border: 1px solid #C3E6CB;
        width: 100%;
        margin-bottom: 0px;
        text-align: center;
        padding: 10px;
      }
    </style>
  </head>
  <body  class=" d-flex flex-column">
            @if(session('errormsg'))
            <p class="errormsg">{{ session('errormsg')}}</p>
            @endif
    <script src="/js/demo-theme.min.js"></script>
    <div class="page page-center">
    <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="./img/LogomarcaSVG.svg" width="110" height="32" alt="" class="navbar-brand-image">
          </a>
        </div>
        <div class="card card-md">
          <div class="card-body"> 
            <h2 class="h2 text-center mb-4">Entrar na sua conta</h2>
            <form action="{{ route('login') }}" method="POST" autocomplete="off">
                @csrf 
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input id="email" type="text" name="email" value="{{ old('email') }}" class="form-control" placeholder="" required autocomplete="email">
              </div>
              @error('email')
                        <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                        </span>
              @enderror
              <div class="mb-2">
                <label class="form-label">
                {{ __('Senha') }}
                  <span class="form-label-description">
                    @if (Route::has('password.request'))
                    {{ __('Esqueceu a senha?') }}
                    <a href="{{ route('password.request') }}">Recuperar senha</a>
                    @endif
                  </span>
                </label>  
                <div class="input-group input-group-flat">
                  <input id="password" type="password" class="form-control" name="password"  placeholder=""  required autocomplete="password">
                </div>
              </div>
              @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span> 
              @enderror
              <!-- <div class="mb-2">
                <label class="form-check">
                  <input type="checkbox" id="remember_me" name="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}/>
                  <span class="form-check-label">{{ __('Lembrar-me') }}</span>
                </label>
              </div> -->
              <div class="form-footer">
                <button class="btn btn-primary w-100">
                  {{ __('Entrar') }}
                </button>
              </div>
            </form>
          </div>
       
      </div>
    </div>
    <!-- Libs JS -->
    <!-- Tabler Core -->
    <script src="./js/tabler.min.js" defer></script>
    <script src="./js/demo.min.js" defer></script>
  </body>
</html>