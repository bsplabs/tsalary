<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>tSalary - Login</title>

    @include('shared.head')
</head>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="/" style=""><b>t</b>Salary</a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
        @if ($errors->any())
            <div class="alert alert-default-danger alert-dismissible rounded-0">
                <h6><i class="icon fas fa-ban"></i> เข้าสู่ระบบไม่สำเร็จ</h6>
                <ul style="font-size: 14px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-body login-card-body">
            <p class="login-box-msg">เข้าสู่ระบบเพื่อใช้งานระบบ Payroll</p>
            <form action="/login" method="post">
                @csrf
                <div class="input-group mb-3">
                    <input type="text" name="username" id="username"
                           class="form-control {{ ($errors->has('username')) ? 'is-invalid' : '' }}" placeholder="ชื่อผู้ใช้"
                           value="{{ old('username') }}">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password"
                           class="form-control @error('password') is-invalid @enderror" placeholder="รหัสผ่าน">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-4">
                        <button name="logInSubmit" id="logInSubmit" class="btn btn-primary btn-block">เข้าสู่ระบบ
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
<!-- /.login-box -->

@include('shared.end')

<script>
    // var Toast = Swal.mixin({
    //     toast: true,
    //     position: 'top-end',
    //     showConfirmButton: false,
    //     timer: 3000
    // });
    //
    // $(function() {
    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     });
    // });
    //
    // $("#logInSubmit").click(function(event) {
    //     event.preventDefault();
    //
    //     let email = $("#email").val();
    //     let password = $("#password").val();
    //
    //     $.ajax({
    //         url: "/login",
    //         type: "POST",
    //         data: {
    //             email: email,
    //             password: password
    //         },
    //         success: function(res) {
    //             console.log(res);
    //             if (res.error == 0) {
    //                 window.location = "/";
    //             }
    //
    //             if (res.error == 1) {
    //                 toastr.error(res.errorMessages.email[0]);
    //                 toastr.error(res.errorMessages.password[0]);
    //             }
    //
    //             if (res.error == 2) {
    //                 toastr.error(res.errorMessages);
    //             }
    //         },
    //         error: function(jqXHR, exception) {
    //             console.log(jqXHR);
    //             console.log(exception);
    //         }
    //     });
    // });
</script>
</body>

</html>
