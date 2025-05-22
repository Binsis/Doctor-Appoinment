<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>User Registration</h2>

    @if($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form id="registerForm" action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-3 fv-row">
            <label>Name:</label><span style="color:red";>*</span>
            <input type="text" name="name" class="form-control"/>
        </div>
        <div class="mb-3 fv-row">
            <label>Email:</label><span style="color:red";>*</span>
            <input type="email" name="email" class="form-control" />
        </div>
        <div class="mb-3 fv-row">
            <label>Password:</label> <span style="color:red";>*</span>
            <input type="password" name="password" id="password" class="form-control" />
        </div>
        <div class="mb-3 fv-row">
            <label>Confirm Password:</label> <span style="color:red";>*</span>
            <input type="password" name="password_confirmation" class="form-control" />
        </div>
        <button type="submit" class="btn btn-success">Register</button>
    </form>

    <div class="mt-3">
        <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
    </div>
</div>
@section('scripts')
<script>
$(document).ready(function() {
  $("#registerForm").validate({
         rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        },
               message: {
            name: {
                required: "Please enter your name"
            },
            email: {
                required: "Please enter your email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter your password",
                minlength: "Password must be at least 6 characters"
            },
            password_confirmation: {
                required: "Please confirm your password",
                equalTo: "Passwords do not match"
            }
        },
        errorElement: "div",
        errorClass: "invalid-feedback",
        errorPlacement: function (error, element) {
            element.closest(".fv-row").append(error);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid").addClass("is-valid");
        }
    });
});
</script>
@endsection
</body>
</html>
