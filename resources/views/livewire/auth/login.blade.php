<div>
    <div class="container-fluid p-0">
        <div class="row no-gutters">
            <div class="col-lg-12">
                <div class="authentication-page-content p-4 d-flex align-items-center min-vh-100">
                    <div class="w-100">
                        <div class="row justify-content-center">
                            <div class="col-lg-9">
                                <div>
                                    <div class="text-center">
                                        <div>
                                            <a href="" class="logo"><img style="width: 250px;"
                                                    src={{ asset('assets/images/logo.png') }} alt="logo"></a>
                                        </div>

                                        <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                        <p class="text-muted">Sign in to start your session.</p>
                                    </div>

                                    {{-- <div class="col-lg-12">
                                           <?php echo $this->session->flashdata('result'); ?>
                                       </div> --}}

                                    <div class="p-2 mt-5">
                                        <form wire:submit.prevent="login" class="form-horizontal" method="POST"
                                            id="loginForm">

                                            @if (session()->has('error'))
                                                <div class="alert alert-danger">
                                                    {{ session('error') }}
                                                </div>
                                            @endif

                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                <label for="username">Username</label>
                                                <input type="text" class="form-control" wire:model="username"
                                                    id="username" placeholder="Enter username">
                                                @error('username')
                                                    <span>{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                <label for="userpassword">Password</label>
                                                <input type="password" class="form-control" wire:model="password"
                                                    id="password" placeholder="Enter password">
                                                @error('password')
                                                    <span>{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="mt-4 text-center">
                                                <button class="btn btn-primary w-md waves-effect waves-light"
                                                    type="submit">Log In</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
