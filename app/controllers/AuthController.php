<?php   

class AuthController {
    private AuthService $authService;
    
    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }

    public function register(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        
        
        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }
    
        $request = new RegisterDtoRequest($data);
        $result = $this->authService->register($request);

        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function login(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }
    
        $request = new LoginDtoRequest($data);
        $result = $this->authService->login($request);

        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function forgotPassword() {

        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new ForgotPasswordDtoRequest($data);
        $result = $this->authService->forgotPassword($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    
    
    public function verifyOtp() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new VerifyOtpDtoRequest($data);
        $result = $this->authService->verifyOtp($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }

    public function resetPassword() {
        $data = json_decode(file_get_contents('php://input'), true);

        if ($data === null) {
            Response::json([
                'success' => false,
                'message' => 'Invalid JSON input.'
            ], 400);
            return;
        }

        $request = new resetPasswordDtoRequest($data);
        $result = $this->authService->resetPassword($request);
        $status = $result['status'] ?? ($result['success'] ? 200 : 400);
        Response::json($result, $status);
    }
    

}