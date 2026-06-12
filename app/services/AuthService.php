<?php

class AuthService {
    private IUserRepository $userRepository;

    public function __construct(
        IUserRepository $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function register(
        RegisterDtoRequest $request
    ): array {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        if ($this->userRepository->checkExitsByUsername($request->username))
            return ['success' => false, 'message' => 'Username already exists.'];
        
        if ($this->userRepository->checkExitsByEmail($request->email))
            return ['success' => false, 'message' => 'Email already exists.'];

        $user = UserMapper::RegisterDtoRequestToUsers($request);
        $saveResult = $this->userRepository->save($user);

        if ($saveResult) 
            return ['success' => true, 'message' => 'Registration successful.'];
        else 
            return ['success' => false, 'message' => 'Failed to register user.'];
    }

    public function login(
        LoginDtoRequest $request
    ): array {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        $user = $this->userRepository->findByUsername($request->username);

        if (!$user || !password_verify($request->password, $user->getPassword())) {
            return ['success' => false, 
                    'message' => 'Invalid username or password.'];
        }

        return [
            'success' => true,
            'message' => 'Login successful.',
            'role' => $user->getRole()
        ];
    }
}