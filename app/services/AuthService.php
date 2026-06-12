<?php

class AuthService {
    private $redis;
    private MailService $mailService;
    private IUserRepository $userRepository;

    public function __construct(
        IUserRepository $userRepository
    ) {
        $config = require __DIR__ . '/../../config/redisConf.php';
        $this->redis = new Predis\Client($config);
        $this->mailService = new MailService();
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

    public function forgotPassword(
        ForgotPasswordDtoRequest $request
    ): array
    {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => 'Invalid email format.'];
        }

        $user = $this->userRepository->findByEmail($request->email);

        if (!$user) 
            return ['success' => false, 'message' => 'Email not found.'];
        
        $cooldownKey = "forgotPassword:cooldown:$request->email";
        if ($this->redis->exists($cooldownKey))
            return ['success' => false, 'message' => 'Please wait before requesting another OTP'];

        $requestId = bin2hex(random_bytes(16));
        $otp = random_int(100000, 999999);
        $otpKey = "forgotPassword:otp:$requestId";

        $this->redis->setex($otpKey, 300, json_encode([
            "email" => $request->email,
            "otp" => password_hash((string)$otp, PASSWORD_BCRYPT),
            "verified" => false
        ]));

        $this->redis->setex($cooldownKey, 60, '1');

        $this->mailService->sendEmail($request->email, (string)$otp);

        return [
            'status' => 200,
            'message' => 'OTP sent',
            'requestId' => $requestId
        ];
    }

     public function verifyOtp(
        VerifyOtpDtoRequest $request
     ): array
    {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        $otpKey = "forgotPassword:otp:$request->requestId";
        $data = $this->redis->get($otpKey);

        if (!$data) {
            return ['status' => 400, 'message' => 'OTP expired or invalid'];
        }

        $payload = json_decode($data, true);

        if (!password_verify($request->otp, $payload['otp'])) {
            return ['status' => 400, 'message' => 'Invalid OTP'];
        }

        $payload['verified'] = true;

        $this->redis->setex($otpKey, 300, json_encode($payload));

        return [
            'status' => 200,
            'message' => 'OTP verified'
        ];
    }

     public function resetPassword(
        resetPasswordDtoRequest $request
     ): array
    {
        $err = $request->validate();

        if ($err) 
            return ['success' => false, 'message' => $err];

        $otpKey = "forgotPassword:otp:$request->requestId";
        $data = $this->redis->get($otpKey);

        if (!$data) {
            return ['status' => 400, 'message' => 'Reset request expired'];
        }

        $payload = json_decode($data, true);

        if (!$payload['verified']) {
            return ['status' => 403, 'message' => 'OTP not verified'];
        }

        $hashedPassword = password_hash($request->newPassword, PASSWORD_BCRYPT);

        $this->userRepository->updatePasswordByEmail(
            $payload['email'],
            $hashedPassword
        );

        $this->redis->del([$otpKey]);

        return [
            'status' => 200,
            'message' => 'Password reset successfully'
        ];
    }
}