<!DOCTYPE html>
<html>

<head>
    <title>Rootments | Login</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('assets/images/favicon_2.png') }}" sizes="32*32" type="image/png">

    <!-- Bootstrap CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <!-- Font / Icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">

    <!-- jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('assets/css/login.css') }}">
    
    <!-- SwalFire -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
   
</head>

<body>
    <div class="form-structor">
        <div class="signup">
            <div class="logo d-flex justify-content-center align-items-center" id="signup">
                <img src="{{ asset('assets/images/logo_1.png') }}" height="50px" alt="">
            </div>
            <!-- <h2 class="form-title" id="signup"><span>or</span>Sign up</h2> -->
        </div>
        <form action="{{ route('login.submit') }}" method="POST">
            @csrf
            <div class="login slide-up">
                <div class="center">
                    <h2 class="form-title" id="login">Log In</h2>
                    <div class="form-holder row">
                        <div class="col-10 mx-auto mb-2">
                            <input type="text" class="form-control" id="empcode" name="emp_code"
                                placeholder="Employee Code" required autofocus>
                        </div>
                        <div class="col-10 mx-auto mb-2">
                            <div class="inpflex">
                                <input type="password" class="form-control border-0" id="password" name="password" placeholder="Password"
                                    required>
                                <i class="fa-solid fa-eye-slash" id="passHide"
                                    onclick="togglePasswordVisibility('password', 'passShow', 'passHide')"
                                    style="display:none; cursor:pointer;"></i>
                                <i class="fa-solid fa-eye" id="passShow"
                                    onclick="togglePasswordVisibility('password', 'passShow', 'passHide')"
                                    style="cursor:pointer;"></i>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="submit-btn">Login</button></a>
                </div>
            </div>
        </form>
    </div>
    
    <!-- SwalFire -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

<script>
    function togglePasswordVisibility(inputId, showId, hideId) {
        let $input = $('#' + inputId);
        let $passShow = $('#' + showId);
        let $passHide = $('#' + hideId);

        if ($input.attr('type') === 'password') {
            $input.attr('type', 'text');
            $passShow.hide();
            $passHide.show();
        } else {
            $input.attr('type', 'password');
            $passShow.show();
            $passHide.hide();
        }
    }
</script>

<script>
    console.clear();

    const loginBtn = document.getElementById('login');
    const signupBtn = document.getElementById('signup');

    loginBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode.parentNode;
        Array.from(e.target.parentNode.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                signupBtn.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });

    signupBtn.addEventListener('click', (e) => {
        let parent = e.target.parentNode;
        Array.from(e.target.parentNode.classList).find((element) => {
            if (element !== "slide-up") {
                parent.classList.add('slide-up')
            } else {
                loginBtn.parentNode.parentNode.classList.add('slide-up')
                parent.classList.remove('slide-up')
            }
        });
    });
</script>
<script>
    @if (Session::has('status'))
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            customClass: {
                title: 'toast-title'
            }
        });

        Toast.fire({
            icon: "{{ Session::get('status') }}",
            title: "{{ Session::get('message') }}",
        });
    @endif
</script>

</html>
