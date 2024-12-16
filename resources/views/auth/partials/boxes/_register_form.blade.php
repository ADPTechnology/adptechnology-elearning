<form method="POST" action="{{ route('home.user.registerExternal') }}" role="form" id="register-form"
    data-validate="{{ route('register.validateEmail') }}" class="text-start login-form d-flex flex-column">

    @csrf


    <div class="container-social-register">
        <div class="social_google">
            <a type="button" href="{{ route('auth.google.redirect') }}"
                class="btn btn-light btn-block d-flex align-items-center justify-content-center"
                style="height: 56px; background-color: black">
                <div class="mr-2">
                    <svg version="1.1" x="0px" y="0px" viewBox="0 0 512 512" width="22" height="22">
                        <path
                            d="M113.47,309.408L95.648,375.94l-65.139,1.378C11.042,341.211,0,299.9,0,256c0-42.451,10.324-82.483,28.624-117.732h0.014l57.992,10.632l25.404,57.644c-5.317,15.501-8.215,32.141-8.215,49.456C103.821,274.792,107.225,292.797,113.47,309.408z"
                            style="fill: rgb(251, 187, 0);"></path>
                        <path
                            d="M507.527,208.176C510.467,223.662,512,239.655,512,256c0,18.328-1.927,36.206-5.598,53.451c-12.462,58.683-45.025,109.925-90.134,146.187l-0.014-0.014l-73.044-3.727l-10.338-64.535c29.932-17.554,53.324-45.025,65.646-77.911h-136.89V208.176h138.887L507.527,208.176z"
                            style="fill: rgb(81, 142, 248);"></path>
                        <path
                            d="M416.253,455.624l0.014,0.014C372.396,490.901,316.666,512,256,512c-97.491,0-182.252-54.491-225.491-134.681l82.961-67.91c21.619,57.698,77.278,98.771,142.53,98.771c28.047,0,54.323-7.582,76.87-20.818L416.253,455.624z"
                            style="fill: rgb(40, 180, 70);"></path>
                        <path
                            d="M419.404,58.936l-82.933,67.896c-23.335-14.586-50.919-23.012-80.471-23.012c-66.729,0-123.429,42.957-143.965,102.724l-83.397-68.276h-0.014C71.23,56.123,157.06,0,256,0C318.115,0,375.068,22.126,419.404,58.936z"
                            style="fill: rgb(241, 67, 54);"></path>
                    </svg>
                </div>
                <span class="mx-2 color-black">Regístrate con Google</span>
            </a>
        </div>
        {{-- <div class="social_facebook">
            <a type="button" href="{{ route('auth.facebook.redirect') }}"
                class="btn btn-primary btn-block d-flex align-items-center justify-content-center"
                style="height: 56px; background-color: #1877F2;">
                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="none"
                    viewBox="0 0 28 28">
                    <path fill="white"
                        d="M14.001.67C6.64.67.67 6.638.67 14c0 6.654 4.875 12.168 11.25 13.172v-9.317H8.532v-3.855h3.387v-2.937c0-3.344 1.99-5.188 5.034-5.188 1.46 0 2.987.26 2.987.26v3.279h-1.685c-1.654 0-2.17 1.029-2.17 2.084v2.5h3.694l-.59 3.854h-3.105v9.318c6.375-.999 11.25-6.515 11.25-13.17C27.333 6.64 21.363.67 14 .67z">
                    </path>
                </svg>
                <span class="mx-2 text-white">Regístrate con Facebook</span>
            </a>
        </div> --}}
    </div>

    <div class="register-principal-form-container">

        <div class="row">

            <div class="input-box my-2 col-12">
                <input type="text" name="name" class="form-control" placeholder="Ingrese su nombre">
            </div>

        </div>

        <div class="row">

            <div class="input-box my-2 col-12 col-md-6">
                <input type="text" name="paternal" class="form-control" placeholder="Ingrese su apellido paterno">
            </div>

            <div class="input-box my-2 col-12 col-md-6">
                <input type="text" name="maternal" class="form-control" placeholder="Ingrese su apellido materno">
            </div>

        </div>


        <div class="row">

            <div class="input-box my-2 col-12">
                <input type="email" name="email" class="form-control" placeholder="Ingrese su correo">
            </div>

        </div>

        <div class="row">
            <div class="input-box my-2 col-12">
                <input type="password" name="password" class="form-control" placeholder="Ingrese su contraseña">
            </div>
        </div>

    </div>

    <div class="my-3 message-form d-flex align-items-center">
        <span>
            <i class="fa-solid fa-circle-exclamation"></i>
        </span>
        <span>
            La contraseña debe tener al menos 8 caracteres y contener una mayúscula, una minúscula, un número y un
            caracter especial.
        </span>
    </div>

    <div class="my-3 message-form">
        <span>
            Las credenciales de inicio de sesión se enviarán al correo ingresado.
        </span>
    </div>

    <div class="text-center btn-login-submit">
        <button type="submit" class="btn my-4 mb-2 ps-5 pe-4 btn-save">
            REGISTRARSE
            &nbsp;
            <i class="fa-solid fa-spinner fa-spin loadSpinner"></i>
        </button>
    </div>

    <div
        class="links-bottom-container d-flex align-items-center flex-column-reverse flex-md-row mt-3 justify-content-md-between">
        <a href="{{ route('home.index') }}">
            <i class="fa-solid fa-angles-left"></i>
            Volver a la página de inicio
        </a>

        <span style="color: white">
            ¿Ya tienes una cuenta?
            <a href="{{ route('login') }}">
                Inicia sesión
            </a>
        </span>

    </div>

</form>
