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
                                            <a href="#" class="logo">
                                                <img style="width: 250px;" src="{{ asset('assets/images/logo.png') }}" alt="logo">
                                            </a>
                                        </div>

                                        <h4 class="font-size-18 mt-4">Welcome Back !</h4>
                                        <p class="text-muted">Sign in to start your session.</p>
                                    </div>

                                    <div class="p-2 mt-5">
                                        <form wire:submit.prevent="login" class="form-horizontal" id="loginForm" autocomplete="off">
                                            {{-- Error global dari validasi / login --}}
                                            @if ($errors->any())
                                                <div class="alert alert-danger">
                                                    {{ $errors->first() }}
                                                </div>
                                            @endif

                                            {{-- Username --}}
                                            <div class="form-group auth-form-group-custom mb-4">
                                                <i class="ri-user-2-line auti-custom-input-icon"></i>
                                                <label for="username">Username</label>
                                                <input
                                                    type="text"
                                                    id="username"
                                                    class="form-control @error('username') is-invalid @enderror"
                                                    wire:model.defer="username"
                                                    placeholder="Enter username"
                                                    autofocus
                                                >
                                                @error('username')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- Password --}}
                                            <div class="form-group auth-form-group-custom mb-2">
                                                <i class="ri-lock-2-line auti-custom-input-icon"></i>
                                                <label for="password">Password</label>
                                                <input
                                                    type="password"
                                                    id="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    wire:model.defer="password"
                                                    placeholder="Enter password"
                                                >
                                                @error('password')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            {{-- Remember me --}}
                                            <div class="form-group d-flex align-items-center justify-content-between mb-4">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="remember" wire:model="remember">
                                                    <label class="custom-control-label" for="remember">Remember me</label>
                                                </div>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <button
                                                    class="btn btn-primary w-md waves-effect waves-light"
                                                    type="submit"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <span wire:loading.remove>Log In</span>
                                                    <span wire:loading>Processing...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div> <!-- end w-100 -->
                </div> <!-- end authentication-page-content -->
            </div>
        </div>
    </div>
</div>
